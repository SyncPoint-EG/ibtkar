<?php

namespace App\Services;

use App\Models\LuckWheelItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LuckWheelItemService
{
    protected LuckWheelItem $model;

    public function __construct(LuckWheelItem $model)
    {
        $this->model = $model;
    }

    /**
     * Get all luckWheelItems with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all luckWheelItems without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find luckWheelItem by ID
     *
     * @param int $id
     * @return LuckWheelItem|null
     */
    public function findById(int $id): ?LuckWheelItem
    {
        return $this->model->find($id);
    }

    /**
     * Find luckWheelItem by ID or fail
     *
     * @param int $id
     * @return LuckWheelItem
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): LuckWheelItem
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new luckWheelItem
     *
     * @param array $data
     * @return LuckWheelItem
     * @throws \Exception
     */
    public function create(array $data): LuckWheelItem
    {
        try {
            DB::beginTransaction();

            $luckWheelItem = $this->model->create($data);

            DB::commit();

            Log::info('LuckWheelItem created successfully', ['id' => $luckWheelItem->id]);

            return $luckWheelItem;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating LuckWheelItem', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing luckWheelItem
     *
     * @param LuckWheelItem $luckWheelItem
     * @param array $data
     * @return LuckWheelItem
     * @throws \Exception
     */
    public function update(LuckWheelItem $luckWheelItem, array $data): LuckWheelItem
    {
        try {
            DB::beginTransaction();

            $luckWheelItem->update($data);
            $luckWheelItem->refresh();

            DB::commit();

            Log::info('LuckWheelItem updated successfully', ['id' => $luckWheelItem->id]);

            return $luckWheelItem;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating LuckWheelItem', [
                'id' => $luckWheelItem->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a luckWheelItem
     *
     * @param LuckWheelItem $luckWheelItem
     * @return bool
     * @throws \Exception
     */
    public function delete(LuckWheelItem $luckWheelItem): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $luckWheelItem->delete();

            DB::commit();

            Log::info('LuckWheelItem deleted successfully', ['id' => $luckWheelItem->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting LuckWheelItem', [
                'id' => $luckWheelItem->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search luckWheelItems based on criteria
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
     * Bulk delete luckWheelItems
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

            Log::info('Bulk delete luckWheelItems completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete luckWheelItems', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get luckWheelItems by specific field
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
     * Count total luckWheelItems
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if luckWheelItem exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest luckWheelItems
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a luckWheelItem
     *
     * @param LuckWheelItem $luckWheelItem
     * @return LuckWheelItem
     * @throws \Exception
     */
    public function duplicate(LuckWheelItem $luckWheelItem): LuckWheelItem
    {
        try {
            DB::beginTransaction();

            $data = $luckWheelItem->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newLuckWheelItem = $this->model->create($data);

            DB::commit();

            Log::info('LuckWheelItem duplicated successfully', [
                'original_id' => $luckWheelItem->id,
                'new_id' => $newLuckWheelItem->id
            ]);

            return $newLuckWheelItem;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating LuckWheelItem', [
                'id' => $luckWheelItem->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
