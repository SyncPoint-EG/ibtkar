<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService
{
    protected Student $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    private function applyFilters($query, array $filters = [])
    {
        if (! empty($filters['name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%'.$filters['name'].'%')
                    ->orWhere('last_name', 'like', '%'.$filters['name'].'%');
            });
        }

        if (! empty($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('last_name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('phone', 'like', '%'.$filters['q'].'%');
            });
        }

        if (! empty($filters['phone'])) {
            $query->where('phone', 'like', '%'.$filters['phone'].'%');
        }

        if (! empty($filters['governorate_id'])) {
            $query->where('governorate_id', $filters['governorate_id']);
        }

        if (! empty($filters['center_id'])) {
            $query->where('center_id', $filters['center_id']);
        }

        if (! empty($filters['stage_id'])) {
            $query->where('stage_id', $filters['stage_id']);
        }

        if (! empty($filters['grade_id'])) {
            $query->where('grade_id', $filters['grade_id']);
        }

        if (! empty($filters['division_id'])) {
            $query->where('division_id', $filters['division_id']);
        }

        if (! empty($filters['education_type_id'])) {
            $query->where('education_type_id', $filters['education_type_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        return $query;
    }

    /**
     * Get all students with pagination
     */
    public function getAllPaginated(int $perPage = 15, array $filters = [], array $with = []): LengthAwarePaginator
    {
        $query = $this->model->with($with);
        $query = $this->applyFilters($query, $filters);
        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all students without pagination
     */
    public function getAll(array $filters = [], array $with = []): Collection
    {
        $query = $this->model->with($with);
        $query = $this->applyFilters($query, $filters);
        return $query->get();
    }

    /**
     * Find student by ID
     */
    public function findById(int $id): ?Student
    {
        return $this->model->find($id);
    }

    /**
     * Find student by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Student
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new student
     *
     * @throws \Exception
     */
    public function create(array $data): Student
    {
        try {
            DB::beginTransaction();

            $student = $this->model->create($data);

            DB::commit();

            Log::info('Student created successfully', ['id' => $student->id]);

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Student', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing student
     *
     * @throws \Exception
     */
    public function update(Student $student, array $data): Student
    {
        try {
            DB::beginTransaction();

            if (! isset($data['password']) || ! $data['password']) {
                unset($data['password']);
            }

            $student->update($data);
            $student->refresh();

            DB::commit();

            Log::info('Student updated successfully', ['id' => $student->id]);

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Student', [
                'id' => $student->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a student
     *
     * @throws \Exception
     */
    public function delete(Student $student): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $student->delete();

            DB::commit();

            Log::info('Student deleted successfully', ['id' => $student->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Student', [
                'id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search students based on criteria
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
     * Bulk delete students
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete students completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete students', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get students by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total students
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if student exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest students
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a student
     *
     * @throws \Exception
     */
    public function duplicate(Student $student): Student
    {
        try {
            DB::beginTransaction();

            $data = $student->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newStudent = $this->model->create($data);

            DB::commit();

            Log::info('Student duplicated successfully', [
                'original_id' => $student->id,
                'new_id' => $newStudent->id,
            ]);

            return $newStudent;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Student', [
                'id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}