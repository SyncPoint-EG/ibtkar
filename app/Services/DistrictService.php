<?php

namespace App\Services;

use App\Models\District;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistrictService
{
    protected District $model;

    public function __construct(District $model)
    {
        $this->model = $model;
    }

    /**
     * Get all districts with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all districts without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find district by ID
     *
     * @param int $id
     * @return District|null
     */
    public function findById(int $id): ?District
    {
        return $this->model->find($id);
    }

    /**
     * Find district by ID or fail
     *
     * @param int $id
     * @return District
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): District
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new district
     *
     * @param array $data
     * @return District
     * @throws \Exception
     */
    public function create(array $data): District
    {
        try {
            DB::beginTransaction();

            $district = $this->model->create($data);

            DB::commit();

            Log::info('District created successfully', ['id' => $district->id]);

            return $district;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating District', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing district
     *
     * @param District $district
     * @param array $data
     * @return District
     * @throws \Exception
     */
    public function update(District $district, array $data): District
    {
        try {
            DB::beginTransaction();

            $district->update($data);
            $district->refresh();

            DB::commit();

            Log::info('District updated successfully', ['id' => $district->id]);

            return $district;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating District', [
                'id' => $district->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a district
     *
     * @param District $district
     * @return bool
     * @throws \Exception
     */
    public function delete(District $district): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $district->delete();

            DB::commit();

            Log::info('District deleted successfully', ['id' => $district->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting District', [
                'id' => $district->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search districts based on criteria
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
     * Bulk delete districts
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

            Log::info('Bulk delete districts completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete districts', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get districts by specific field
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
     * Count total districts
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if district exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest districts
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a district
     *
     * @param District $district
     * @return District
     * @throws \Exception
     */
    public function duplicate(District $district): District
    {
        try {
            DB::beginTransaction();

            $data = $district->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newDistrict = $this->model->create($data);

            DB::commit();

            Log::info('District duplicated successfully', [
                'original_id' => $district->id,
                'new_id' => $newDistrict->id
            ]);

            return $newDistrict;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating District', [
                'id' => $district->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
