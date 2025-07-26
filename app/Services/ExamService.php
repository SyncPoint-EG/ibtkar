<?php

namespace App\Services;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamService
{
    protected Exam $model;

    public function __construct(Exam $model)
    {
        $this->model = $model;
    }

    /**
     * Get all exams with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all exams without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find exam by ID
     *
     * @param int $id
     * @return Exam|null
     */
    public function findById(int $id): ?Exam
    {
        return $this->model->find($id);
    }

    /**
     * Find exam by ID or fail
     *
     * @param int $id
     * @return Exam
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Exam
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new exam
     *
     * @param array $data
     * @return Exam
     * @throws \Exception
     */
    public function create(array $data): Exam
    {
        try {
            DB::beginTransaction();

            $exam = $this->model->create($data);

            DB::commit();

            Log::info('Exam created successfully', ['id' => $exam->id]);

            return $exam;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Exam', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing exam
     *
     * @param Exam $exam
     * @param array $data
     * @return Exam
     * @throws \Exception
     */
    public function update(Exam $exam, array $data): Exam
    {
        try {
            DB::beginTransaction();

            $exam->update($data);
            $exam->refresh();

            DB::commit();

            Log::info('Exam updated successfully', ['id' => $exam->id]);

            return $exam;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Exam', [
                'id' => $exam->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a exam
     *
     * @param Exam $exam
     * @return bool
     * @throws \Exception
     */
    public function delete(Exam $exam): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $exam->delete();

            DB::commit();

            Log::info('Exam deleted successfully', ['id' => $exam->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Exam', [
                'id' => $exam->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search exams based on criteria
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
     * Bulk delete exams
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

            Log::info('Bulk delete exams completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete exams', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get exams by specific field
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
     * Count total exams
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if exam exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest exams
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a exam
     *
     * @param Exam $exam
     * @return Exam
     * @throws \Exception
     */
    public function duplicate(Exam $exam): Exam
    {
        try {
            DB::beginTransaction();

            $data = $exam->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newExam = $this->model->create($data);

            DB::commit();

            Log::info('Exam duplicated successfully', [
                'original_id' => $exam->id,
                'new_id' => $newExam->id
            ]);

            return $newExam;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Exam', [
                'id' => $exam->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
