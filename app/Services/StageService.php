<?php

namespace App\Services;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StageService
{
    protected Stage $model;

    public function __construct(Stage $model)
    {
        $this->model = $model;
    }

    /**
     * Get all stages with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all stages without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find stage by ID
     */
    public function findById(int $id): ?Stage
    {
        return $this->model->find($id);
    }

    /**
     * Find stage by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Stage
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new stage
     *
     * @throws \Exception
     */
    public function create(array $data): Stage
    {
        try {
            DB::beginTransaction();

            $stage = $this->model->create($data);

            DB::commit();

            Log::info('Stage created successfully', ['id' => $stage->id]);

            return $stage;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Stage', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing stage
     *
     * @throws \Exception
     */
    public function update(Stage $stage, array $data): Stage
    {
        try {
            DB::beginTransaction();

            $stage->update($data);
            $stage->refresh();

            DB::commit();

            Log::info('Stage updated successfully', ['id' => $stage->id]);

            return $stage;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Stage', [
                'id' => $stage->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a stage
     *
     * @throws \Exception
     */
    public function delete(Stage $stage): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $stage->delete();

            DB::commit();

            Log::info('Stage deleted successfully', ['id' => $stage->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Stage', [
                'id' => $stage->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search stages based on criteria
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
     * Bulk delete stages
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete stages completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete stages', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get stages by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total stages
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if stage exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest stages
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a stage
     *
     * @throws \Exception
     */
    public function duplicate(Stage $stage): Stage
    {
        try {
            DB::beginTransaction();

            $data = $stage->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newStage = $this->model->create($data);

            DB::commit();

            Log::info('Stage duplicated successfully', [
                'original_id' => $stage->id,
                'new_id' => $newStage->id,
            ]);

            return $newStage;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Stage', [
                'id' => $stage->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
