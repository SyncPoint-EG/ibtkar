<?php

namespace App\Services;

use App\Models\EducationType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EducationTypeService
{
    protected EducationType $model;

    public function __construct(EducationType $model)
    {
        $this->model = $model;
    }

    /**
     * Get all educationTypes with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all educationTypes without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find educationType by ID
     */
    public function findById(int $id): ?EducationType
    {
        return $this->model->find($id);
    }

    /**
     * Find educationType by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): EducationType
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new educationType
     *
     * @throws \Exception
     */
    public function create(array $data): EducationType
    {
        try {
            DB::beginTransaction();

            $educationType = $this->model->create($data);

            DB::commit();

            Log::info('EducationType created successfully', ['id' => $educationType->id]);

            return $educationType;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating EducationType', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing educationType
     *
     * @throws \Exception
     */
    public function update(EducationType $educationType, array $data): EducationType
    {
        try {
            DB::beginTransaction();

            $educationType->update($data);
            $educationType->refresh();

            DB::commit();

            Log::info('EducationType updated successfully', ['id' => $educationType->id]);

            return $educationType;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating EducationType', [
                'id' => $educationType->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a educationType
     *
     * @throws \Exception
     */
    public function delete(EducationType $educationType): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $educationType->delete();

            DB::commit();

            Log::info('EducationType deleted successfully', ['id' => $educationType->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting EducationType', [
                'id' => $educationType->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search educationTypes based on criteria
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
     * Bulk delete educationTypes
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete educationTypes completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete educationTypes', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get educationTypes by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total educationTypes
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if educationType exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest educationTypes
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a educationType
     *
     * @throws \Exception
     */
    public function duplicate(EducationType $educationType): EducationType
    {
        try {
            DB::beginTransaction();

            $data = $educationType->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newEducationType = $this->model->create($data);

            DB::commit();

            Log::info('EducationType duplicated successfully', [
                'original_id' => $educationType->id,
                'new_id' => $newEducationType->id,
            ]);

            return $newEducationType;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating EducationType', [
                'id' => $educationType->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
