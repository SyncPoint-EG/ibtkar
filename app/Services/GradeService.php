<?php

namespace App\Services;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeService
{
    protected Grade $model;

    public function __construct(Grade $model)
    {
        $this->model = $model;
    }

    /**
     * Get all grades with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all grades without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find grade by ID
     */
    public function findById(int $id): ?Grade
    {
        return $this->model->find($id);
    }

    /**
     * Find grade by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Grade
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new grade
     *
     * @throws \Exception
     */
    public function create(array $data): Grade
    {
        try {
            DB::beginTransaction();

            $grade = $this->model->create($data);

            DB::commit();

            Log::info('Grade created successfully', ['id' => $grade->id]);

            return $grade;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Grade', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing grade
     *
     * @throws \Exception
     */
    public function update(Grade $grade, array $data): Grade
    {
        try {
            DB::beginTransaction();

            $grade->update($data);
            $grade->refresh();

            DB::commit();

            Log::info('Grade updated successfully', ['id' => $grade->id]);

            return $grade;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Grade', [
                'id' => $grade->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a grade
     *
     * @throws \Exception
     */
    public function delete(Grade $grade): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $grade->delete();

            DB::commit();

            Log::info('Grade deleted successfully', ['id' => $grade->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Grade', [
                'id' => $grade->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search grades based on criteria
     */
    public function search(array $criteria): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Add search logic based on your model's searchable fields
        // Example implementation:
        if (isset($criteria['search']) && ! empty($criteria['search'])) {
            $searchTerm = $criteria['search'];
            $query->where(function ($q) {
                // Add searchable columns here
                // $q->where('name', 'LIKE', "%{$searchTerm}%")
                //   ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Add date range filtering
        if (isset($criteria['start_date']) && ! empty($criteria['start_date'])) {
            $query->whereDate('created_at', '>=', $criteria['start_date']);
        }

        if (isset($criteria['end_date']) && ! empty($criteria['end_date'])) {
            $query->whereDate('created_at', '<=', $criteria['end_date']);
        }

        // Add sorting
        $sortBy = $criteria['sort_by'] ?? 'created_at';
        $sortOrder = $criteria['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $criteria['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * Bulk delete grades
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete grades completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete grades', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get grades by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total grades
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if grade exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest grades
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a grade
     *
     * @throws \Exception
     */
    public function duplicate(Grade $grade): Grade
    {
        try {
            DB::beginTransaction();

            $data = $grade->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newGrade = $this->model->create($data);

            DB::commit();

            Log::info('Grade duplicated successfully', [
                'original_id' => $grade->id,
                'new_id' => $newGrade->id,
            ]);

            return $newGrade;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Grade', [
                'id' => $grade->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
