<?php

namespace App\Services;

use App\Models\Governorate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GovernorateService
{
    protected Governorate $model;

    public function __construct(Governorate $model)
    {
        $this->model = $model;
    }

    /**
     * Get all governorates with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all governorates without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find governorate by ID
     */
    public function findById(int $id): ?Governorate
    {
        return $this->model->find($id);
    }

    /**
     * Find governorate by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Governorate
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new governorate
     *
     * @throws \Exception
     */
    public function create(array $data): Governorate
    {
        try {
            DB::beginTransaction();

            $governorate = $this->model->create($data);

            DB::commit();

            Log::info('Governorate created successfully', ['id' => $governorate->id]);

            return $governorate;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Governorate', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing governorate
     *
     * @throws \Exception
     */
    public function update(Governorate $governorate, array $data): Governorate
    {
        try {
            DB::beginTransaction();

            $governorate->update($data);
            $governorate->refresh();

            DB::commit();

            Log::info('Governorate updated successfully', ['id' => $governorate->id]);

            return $governorate;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Governorate', [
                'id' => $governorate->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a governorate
     *
     * @throws \Exception
     */
    public function delete(Governorate $governorate): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $governorate->delete();

            DB::commit();

            Log::info('Governorate deleted successfully', ['id' => $governorate->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Governorate', [
                'id' => $governorate->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search governorates based on criteria
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
     * Bulk delete governorates
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete governorates completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete governorates', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get governorates by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total governorates
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if governorate exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest governorates
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a governorate
     *
     * @throws \Exception
     */
    public function duplicate(Governorate $governorate): Governorate
    {
        try {
            DB::beginTransaction();

            $data = $governorate->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newGovernorate = $this->model->create($data);

            DB::commit();

            Log::info('Governorate duplicated successfully', [
                'original_id' => $governorate->id,
                'new_id' => $newGovernorate->id,
            ]);

            return $newGovernorate;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Governorate', [
                'id' => $governorate->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
