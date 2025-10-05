<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseService
{
    protected Course $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    /**
     * Get all courses with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        $perPage = request()->perPage ?? $perPage;

        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all courses without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find course by ID
     */
    public function findById(int $id): ?Course
    {
        return $this->model->find($id);
    }

    /**
     * Find course by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Course
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new course
     *
     * @throws \Exception
     */
    public function create(array $data): Course
    {
        try {
            DB::beginTransaction();

            $course = $this->model->create($data);

            DB::commit();

            Log::info('Course created successfully', ['id' => $course->id]);

            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Course', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing course
     *
     * @throws \Exception
     */
    public function update(Course $course, array $data): Course
    {
        try {
            DB::beginTransaction();

            $course->update($data);
            $course->refresh();

            DB::commit();

            Log::info('Course updated successfully', ['id' => $course->id]);

            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Course', [
                'id' => $course->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a course
     *
     * @throws \Exception
     */
    public function delete(Course $course): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $course->delete();

            DB::commit();

            Log::info('Course deleted successfully', ['id' => $course->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Course', [
                'id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search courses based on criteria
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
     * Bulk delete courses
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete courses completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete courses', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get courses by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total courses
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if course exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest courses
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a course
     *
     * @throws \Exception
     */
    public function duplicate(Course $course): Course
    {
        try {
            DB::beginTransaction();

            $data = $course->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newCourse = $this->model->create($data);

            DB::commit();

            Log::info('Course duplicated successfully', [
                'original_id' => $course->id,
                'new_id' => $newCourse->id,
            ]);

            return $newCourse;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Course', [
                'id' => $course->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getStudents(Course $course)
    {
        $students = new Collection;
        $payments = $course->payments()->with('student')->get();
        foreach ($payments as $payment) {
            if ($payment->student) {
                $students->add($payment->student);
            }
        }

        $students = $students->unique('id');

        foreach ($students as $student) {
            $watches = 0;
            foreach ($course->chapters as $chapter) {
                foreach ($chapter->lessons as $lesson) {
                    $watch = $student->watches()->where('lesson_id', $lesson->id)->first();
                    if ($watch) {
                        $watches += $watch->watches;
                    }
                }
            }
            $student->watches_count = $watches;
        }

        return $students;
    }
}
