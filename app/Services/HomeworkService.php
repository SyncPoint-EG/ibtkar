<?php

namespace App\Services;

use App\Models\Homework;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeworkService
{
    protected Homework $model;

    public function __construct(Homework $model)
    {
        $this->model = $model;
    }

    /**
     * Get all homework with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all homework without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find homework by ID
     */
    public function findById(int $id): ?Homework
    {
        return $this->model->find($id);
    }

    /**
     * Find homework by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Homework
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new homework
     *
     * @throws \Exception
     */
    public function create(array $data): Homework
    {
        try {
            DB::beginTransaction();

            $homework = $this->model->create($data);

            DB::commit();

            Log::info('Homework created successfully', ['id' => $homework->id]);

            return $homework;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Homework', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing homework
     *
     * @throws \Exception
     */
    public function update(Homework $homework, array $data): Homework
    {
        try {
            DB::beginTransaction();

            $homework->update($data);
            $homework->refresh();

            DB::commit();

            Log::info('Homework updated successfully', ['id' => $homework->id]);

            return $homework;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Homework', [
                'id' => $homework->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a homework
     *
     * @throws \Exception
     */
    public function delete(Homework $homework): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $homework->delete();

            DB::commit();

            Log::info('Homework deleted successfully', ['id' => $homework->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Homework', [
                'id' => $homework->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search homework based on criteria
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
     * Bulk delete homework
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete homework completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete homework', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get homework by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total homework
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if homework exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest homework
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a homework
     *
     * @throws \Exception
     */
    public function duplicate(Homework $homework): Homework
    {
        try {
            DB::beginTransaction();

            $data = $homework->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newHomework = $this->model->create($data);

            DB::commit();

            Log::info('Homework duplicated successfully', [
                'original_id' => $homework->id,
                'new_id' => $newHomework->id,
            ]);

            return $newHomework;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Homework', [
                'id' => $homework->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
