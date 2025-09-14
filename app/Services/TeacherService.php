<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    protected Teacher $model;

    public function __construct(Teacher $model)
    {
        $this->model = $model;
    }

    /**
     * Get all teachers with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all teachers without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find teacher by ID
     *
     * @param int $id
     * @return Teacher|null
     */
    public function findById(int $id): ?Teacher
    {
        return $this->model->find($id);
    }

    /**
     * Find teacher by ID or fail
     *
     * @param int $id
     * @return Teacher
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Teacher
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new teacher
     *
     * @param array $data
     * @return Teacher
     * @throws \Exception
     */
    public function create(array $data): Teacher
    {
        try {
            DB::beginTransaction();

            $teacher = $this->model->create($data);

            DB::commit();

            Log::info('Teacher created successfully', ['id' => $teacher->id]);

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Teacher', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing teacher
     *
     * @param Teacher $teacher
     * @param array $data
     * @return Teacher
     * @throws \Exception
     */
    public function update(Teacher $teacher, array $data): Teacher
    {
        try {
            DB::beginTransaction();

            $teacher->update($data);
            $teacher->refresh();

            DB::commit();

            Log::info('Teacher updated successfully', ['id' => $teacher->id]);

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a teacher
     *
     * @param Teacher $teacher
     * @return bool
     * @throws \Exception
     */
    public function delete(Teacher $teacher): bool
    {
        try {
            DB::beginTransaction();

            $teacher->subjects()->detach();
            $deleted = $teacher->delete();

            DB::commit();

            Log::info('Teacher deleted successfully', ['id' => $teacher->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search teachers based on criteria
     *
     * @param array $criteria
     * @return LengthAwarePaginator
     */
    public function search(array $criteria): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Add search logic based on your model's searchable fields
        // Example implementation:
        if (isset($criteria['search']) && !empty($criteria['search'])) {
            $searchTerm = $criteria['search'];
            $query->where(function ($q) use ($searchTerm) {
                // Add searchable columns here
                // $q->where('name', 'LIKE', "%{$searchTerm}%")
                //   ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Add date range filtering
        if (isset($criteria['start_date']) && !empty($criteria['start_date'])) {
            $query->whereDate('created_at', '>=', $criteria['start_date']);
        }

        if (isset($criteria['end_date']) && !empty($criteria['end_date'])) {
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
     * Bulk delete teachers
     *
     * @param array $ids
     * @return int
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete teachers completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete teachers', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get teachers by specific field
     *
     * @param string $field
     * @param mixed $value
     * @return Collection
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total teachers
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if teacher exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest teachers
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a teacher
     *
     * @param Teacher $teacher
     * @return Teacher
     * @throws \Exception
     */
    public function duplicate(Teacher $teacher): Teacher
    {
        try {
            DB::beginTransaction();

            $data = $teacher->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newTeacher = $this->model->create($data);

            DB::commit();

            Log::info('Teacher duplicated successfully', [
                'original_id' => $teacher->id,
                'new_id' => $newTeacher->id
            ]);

            return $newTeacher;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
