<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BannerService
{
    protected Banner $model;

    public function __construct(Banner $model)
    {
        $this->model = $model;
    }

    /**
     * Get all banners with pagination
     */
    public function getAllPaginated(int $perPage = 15, $with = []): LengthAwarePaginator
    {
        $perPage = request()->perPage ?? $perPage;

        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all banners without pagination
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find banner by ID
     */
    public function findById(int $id): ?Banner
    {
        return $this->model->find($id);
    }

    /**
     * Find banner by ID or fail
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Banner
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new banner
     *
     * @throws \Exception
     */
    public function create(array $data): Banner
    {
        try {
            DB::beginTransaction();

            $banner = $this->model->create($data);

            DB::commit();

            Log::info('Banner created successfully', ['id' => $banner->id]);

            return $banner;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Banner', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing banner
     *
     * @throws \Exception
     */
    public function update(Banner $banner, array $data): Banner
    {
        try {
            DB::beginTransaction();

            $banner->update($data);
            $banner->refresh();

            DB::commit();

            Log::info('Banner updated successfully', ['id' => $banner->id]);

            return $banner;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Banner', [
                'id' => $banner->id,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a banner
     *
     * @throws \Exception
     */
    public function delete(Banner $banner): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $banner->delete();

            DB::commit();

            Log::info('Banner deleted successfully', ['id' => $banner->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Banner', [
                'id' => $banner->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search banners based on criteria
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
     * Bulk delete banners
     *
     * @throws \Exception
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $deleted = $this->model->whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete banners completed', [
                'ids' => $ids,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete banners', [
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get banners by specific field
     *
     * @param  mixed  $value
     */
    public function getByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Count total banners
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if banner exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest banners
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a banner
     *
     * @throws \Exception
     */
    public function duplicate(Banner $banner): Banner
    {
        try {
            DB::beginTransaction();

            $data = $banner->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newBanner = $this->model->create($data);

            DB::commit();

            Log::info('Banner duplicated successfully', [
                'original_id' => $banner->id,
                'new_id' => $newBanner->id,
            ]);

            return $newBanner;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Banner', [
                'id' => $banner->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
