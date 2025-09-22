<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonService
{
    protected Lesson $model;

    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }

    /**
     * Get all lessons with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15, array $filters = [], array $with = []): LengthAwarePaginator
    {
        return $this->model
            ->when($filters['teacher_id'] ?? null, function ($query, $teacher_id) {
                return $query->whereHas('chapter.course', function ($query) use ($teacher_id) {
                    $query->where('teacher_id', $teacher_id);
                });
            })
            ->when($filters['course_id'] ?? null, function ($query, $course_id) {
                return $query->whereHas('chapter', function ($query) use ($course_id) {
                    $query->where('course_id', $course_id);
                });
            })
            ->when($filters['chapter_id'] ?? null, function ($query, $chapter_id) {
                $query->where('chapter_id', $chapter_id);
            })
            ->when($filters['name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($filters['created_at'] ?? null, function ($query, $created_at) {
                $query->whereDate('created_at', $created_at);
            })
            ->with($with)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all lessons without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find lesson by ID
     *
     * @param int $id
     * @return Lesson|null
     */
    public function findById(int $id): ?Lesson
    {
        return $this->model->find($id);
    }

    /**
     * Find lesson by ID or fail
     *
     * @param int $id
     * @return Lesson
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Lesson
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new lesson
     *
     * @param array $data
     * @return Lesson
     * @throws \Exception
     */
    public function create(array $data): Lesson
    {
        try {
            DB::beginTransaction();

            $lesson = $this->model->create($data);

            DB::commit();

            Log::info('Lesson created successfully', ['id' => $lesson->id]);

            return $lesson;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Lesson', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing lesson
     *
     * @param Lesson $lesson
     * @param array $data
     * @return Lesson
     * @throws \Exception
     */
    public function update(Lesson $lesson, array $data): Lesson
    {
        try {
            DB::beginTransaction();

            $lesson->update($data);
            $lesson->refresh();

            DB::commit();

            Log::info('Lesson updated successfully', ['id' => $lesson->id]);

            return $lesson;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Lesson', [
                'id' => $lesson->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a lesson
     *
     * @param Lesson $lesson
     * @return bool
     * @throws \Exception
     */
    public function delete(Lesson $lesson): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $lesson->delete();

            DB::commit();

            Log::info('Lesson deleted successfully', ['id' => $lesson->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Lesson', [
                'id' => $lesson->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search lessons based on criteria
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
     * Bulk delete lessons
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

            Log::info('Bulk delete lessons completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete lessons', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get lessons by specific field
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
     * Count total lessons
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if lesson exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest lessons
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a lesson
     *
     * @param Lesson $lesson
     * @return Lesson
     * @throws \Exception
     */
    public function duplicate(Lesson $lesson): Lesson
    {
        try {
            DB::beginTransaction();

            $data = $lesson->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newLesson = $this->model->create($data);

            DB::commit();

            Log::info('Lesson duplicated successfully', [
                'original_id' => $lesson->id,
                'new_id' => $newLesson->id
            ]);

            return $newLesson;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Lesson', [
                'id' => $lesson->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getStudents(Lesson $lesson)
    {
        $students = new Collection();
        $payments = $lesson->payments()->with('student')->get();
        foreach ($payments as $payment) {
            if ($payment->student) {
                $students->add($payment->student);
            }
        }

        $students = $students->unique('id');

        foreach ($students as $student) {
            $watch = $student->watches()->where('lesson_id', $lesson->id)->first();
            $student->watches_count = $watch ? $watch->watches : 0;
        }

        return $students;
    }
}
