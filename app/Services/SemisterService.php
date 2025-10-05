<?php

namespace App\Services;

use App\Models\Semister;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemisterService
{
    protected Semister $model;

    public function __construct(Semister $model)
    {
        $this->model = $model;
    }

    /**
     * Get all semisters with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all semisters without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find semister by ID
     */
    public function findById(int $id): ?Semister
    {
        return $this->model->find($id);
    }

    /**
     * Find semister by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Semister
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new semister
     *
     * @throws \Exception
     */
    public function create(array $data): Semister
    {
        try {
            DB::beginTransaction();

            $semister = $this->model->create($data);

            DB::commit();

            Log::info('Semister created successfully', ['id' => $semister->id]);

            return $semister;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Semister', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing semister
     *
     * @throws \Exception
     */
    public function update(Semister $semister, array $data): Semister
    {
        try {
            DB::beginTransaction();

            $semister->update($data);
            $semister->refresh();

            DB::commit();

            Log::info('Semister updated successfully', ['id' => $semister->id]);

            return $semister;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Semister', [
                'id' => $semister->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a semister
     *
     * @throws \Exception
     */
    public function delete(Semister $semister): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $semister->delete();

            DB::commit();

            Log::info('Semister deleted successfully', ['id' => $semister->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Semister', [
                'id' => $semister->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search semisters based on criteria
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
     * Bulk delete semisters
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete semisters completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete semisters', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get semisters by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total semisters
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if semister exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest semisters
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a semister
     *
     * @throws \Exception
     */
    public function duplicate(Semister $semister): Semister
    {
        try {
            DB::beginTransaction();

            $data = $semister->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newSemister = $this->model->create($data);

            DB::commit();

            Log::info('Semister duplicated successfully', [
                'original_id' => $semister->id,
                'new_id' => $newSemister->id,
            ]);

            return $newSemister;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Semister', [
                'id' => $semister->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
