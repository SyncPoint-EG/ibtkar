<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectService
{
    protected Subject $model;

    public function __construct(Subject $model)
    {
        $this->model = $model;
    }

    /**
     * Get all subjects with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        $perPage = request()->perPage ?? $perPage;

        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all subjects without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find subject by ID
     */
    public function findById(int $id): ?Subject
    {
        return $this->model->find($id);
    }

    /**
     * Find subject by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Subject
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new subject
     *
     * @throws \Exception
     */
    public function create(array $data): Subject
    {
        try {
            DB::beginTransaction();

            $subject = $this->model->create($data);

            DB::commit();

            Log::info('Subject created successfully', ['id' => $subject->id]);

            return $subject;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Subject', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing subject
     *
     * @throws \Exception
     */
    public function update(Subject $subject, array $data): Subject
    {
        try {
            DB::beginTransaction();

            $subject->update($data);
            $subject->refresh();

            DB::commit();

            Log::info('Subject updated successfully', ['id' => $subject->id]);

            return $subject;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Subject', [
                'id' => $subject->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a subject
     *
     * @throws \Exception
     */
    public function delete(Subject $subject): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $subject->delete();

            DB::commit();

            Log::info('Subject deleted successfully', ['id' => $subject->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Subject', [
                'id' => $subject->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search subjects based on criteria
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
     * Bulk delete subjects
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete subjects completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete subjects', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get subjects by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total subjects
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if subject exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest subjects
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a subject
     *
     * @throws \Exception
     */
    public function duplicate(Subject $subject): Subject
    {
        try {
            DB::beginTransaction();

            $data = $subject->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newSubject = $this->model->create($data);

            DB::commit();

            Log::info('Subject duplicated successfully', [
                'original_id' => $subject->id,
                'new_id' => $newSubject->id,
            ]);

            return $newSubject;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Subject', [
                'id' => $subject->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
