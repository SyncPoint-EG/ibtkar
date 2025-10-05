<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DivisionService
{
    protected Division $model;

    public function __construct(Division $model)
    {
        $this->model = $model;
    }

    /**
     * Get all divisions with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all divisions without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find division by ID
     */
    public function findById(int $id): ?Division
    {
        return $this->model->find($id);
    }

    /**
     * Find division by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Division
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new division
     *
     * @throws \Exception
     */
    public function create(array $data): Division
    {
        try {
            DB::beginTransaction();

            $division = $this->model->create($data);

            DB::commit();

            Log::info('Division created successfully', ['id' => $division->id]);

            return $division;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Division', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing division
     *
     * @throws \Exception
     */
    public function update(Division $division, array $data): Division
    {
        try {
            DB::beginTransaction();

            $division->update($data);
            $division->refresh();

            DB::commit();

            Log::info('Division updated successfully', ['id' => $division->id]);

            return $division;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Division', [
                'id' => $division->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a division
     *
     * @throws \Exception
     */
    public function delete(Division $division): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $division->delete();

            DB::commit();

            Log::info('Division deleted successfully', ['id' => $division->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Division', [
                'id' => $division->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search divisions based on criteria
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
     * Bulk delete divisions
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete divisions completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete divisions', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get divisions by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total divisions
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if division exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest divisions
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a division
     *
     * @throws \Exception
     */
    public function duplicate(Division $division): Division
    {
        try {
            DB::beginTransaction();

            $data = $division->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newDivision = $this->model->create($data);

            DB::commit();

            Log::info('Division duplicated successfully', [
                'original_id' => $division->id,
                'new_id' => $newDivision->id,
            ]);

            return $newDivision;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Division', [
                'id' => $division->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
