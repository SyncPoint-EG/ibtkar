<?php

namespace App\Services;

use App\Models\Center;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CenterService
{
    protected Center $model;

    public function __construct(Center $model)
    {
        $this->model = $model;
    }

    /**
     * Get all centers with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all centers without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find center by ID
     */
    public function findById(int $id): ?Center
    {
        return $this->model->find($id);
    }

    /**
     * Find center by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Center
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new center
     *
     * @throws \Exception
     */
    public function create(array $data): Center
    {
        try {
            DB::beginTransaction();

            $center = $this->model->create($data);

            DB::commit();

            Log::info('Center created successfully', ['id' => $center->id]);

            return $center;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Center', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing center
     *
     * @throws \Exception
     */
    public function update(Center $center, array $data): Center
    {
        try {
            DB::beginTransaction();

            $center->update($data);
            $center->refresh();

            DB::commit();

            Log::info('Center updated successfully', ['id' => $center->id]);

            return $center;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Center', [
                'id' => $center->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a center
     *
     * @throws \Exception
     */
    public function delete(Center $center): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $center->delete();

            DB::commit();

            Log::info('Center deleted successfully', ['id' => $center->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Center', [
                'id' => $center->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search centers based on criteria
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
     * Bulk delete centers
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete centers completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete centers', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get centers by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total centers
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if center exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest centers
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a center
     *
     * @throws \Exception
     */
    public function duplicate(Center $center): Center
    {
        try {
            DB::beginTransaction();

            $data = $center->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newCenter = $this->model->create($data);

            DB::commit();

            Log::info('Center duplicated successfully', [
                'original_id' => $center->id,
                'new_id' => $newCenter->id,
            ]);

            return $newCenter;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Center', [
                'id' => $center->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
