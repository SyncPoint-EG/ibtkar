<?php

namespace App\Services;

use App\Models\Guardian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuardianService
{
    protected Guardian $model;

    public function __construct(Guardian $model)
    {
        $this->model = $model;
    }

    /**
     * Get all guardians with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all guardians without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find guardian by ID
     *
     * @param int $id
     * @return Guardian|null
     */
    public function findById(int $id): ?Guardian
    {
        return $this->model->find($id);
    }

    /**
     * Find guardian by ID or fail
     *
     * @param int $id
     * @return Guardian
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Guardian
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new guardian
     *
     * @param array $data
     * @return Guardian
     * @throws \Exception
     */
    public function create(array $data): Guardian
    {
        try {
            DB::beginTransaction();

            $guardian = $this->model->create($data);

            DB::commit();

            Log::info('Guardian created successfully', ['id' => $guardian->id]);

            return $guardian;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Guardian', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing guardian
     *
     * @param Guardian $guardian
     * @param array $data
     * @return Guardian
     * @throws \Exception
     */
    public function update(Guardian $guardian, array $data): Guardian
    {
        try {
            DB::beginTransaction();

            $guardian->update($data);
            $guardian->refresh();

            DB::commit();

            Log::info('Guardian updated successfully', ['id' => $guardian->id]);

            return $guardian;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Guardian', [
                'id' => $guardian->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a guardian
     *
     * @param Guardian $guardian
     * @return bool
     * @throws \Exception
     */
    public function delete(Guardian $guardian): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $guardian->delete();

            DB::commit();

            Log::info('Guardian deleted successfully', ['id' => $guardian->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Guardian', [
                'id' => $guardian->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search guardians based on criteria
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
     * Bulk delete guardians
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

            Log::info('Bulk delete guardians completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete guardians', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get guardians by specific field
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
     * Count total guardians
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if guardian exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest guardians
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a guardian
     *
     * @param Guardian $guardian
     * @return Guardian
     * @throws \Exception
     */
    public function duplicate(Guardian $guardian): Guardian
    {
        try {
            DB::beginTransaction();

            $data = $guardian->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newGuardian = $this->model->create($data);

            DB::commit();

            Log::info('Guardian duplicated successfully', [
                'original_id' => $guardian->id,
                'new_id' => $newGuardian->id
            ]);

            return $newGuardian;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Guardian', [
                'id' => $guardian->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
