<?php

namespace App\Services;

use App\Models\Code;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CodeService
{
    protected Code $model;

    public function __construct(Code $model)
    {
        $this->model = $model;
    }

    /**
     * Get all codes with pagination
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15 , $with = []): LengthAwarePaginator
    {
        return $this->model->with($with)->latest()->paginate($perPage);
    }

    /**
     * Get all codes without pagination
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find code by ID
     *
     * @param int $id
     * @return Code|null
     */
    public function findById(int $id): ?Code
    {
        return $this->model->find($id);
    }

    /**
     * Find code by ID or fail
     *
     * @param int $id
     * @return Code
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Code
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new code
     *
     * @param array $data
     * @return Code
     * @throws \Exception
     */
    public function create(array $data): Code
    {
        try {
            DB::beginTransaction();

            $code = $this->model->create($data);

            DB::commit();

            Log::info('Code created successfully', ['id' => $code->id]);

            return $code;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Code', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing code
     *
     * @param Code $code
     * @param array $data
     * @return Code
     * @throws \Exception
     */
    public function update(Code $code, array $data): Code
    {
        try {
            DB::beginTransaction();

            $code->update($data);
            $code->refresh();

            DB::commit();

            Log::info('Code updated successfully', ['id' => $code->id]);

            return $code;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Code', [
                'id' => $code->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a code
     *
     * @param Code $code
     * @return bool
     * @throws \Exception
     */
    public function delete(Code $code): bool
    {
        try {
            DB::beginTransaction();

            $deleted = $code->delete();

            DB::commit();

            Log::info('Code deleted successfully', ['id' => $code->id]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Code', [
                'id' => $code->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search codes based on criteria
     *
     * @param array $criteria
     * @return LengthAwarePaginator
     */
    public function search(array $criteria, int $perPage = 15, array $with = []): LengthAwarePaginator
    {
        $query = $this->model->with($with);

        if (isset($criteria['teacher_id']) && !empty($criteria['teacher_id'])) {
            $query->where('teacher_id', $criteria['teacher_id']);
        }

        if (isset($criteria['expires_at']) && !empty($criteria['expires_at'])) {
            $query->whereDate('expires_at', $criteria['expires_at']);
        }

        if (isset($criteria['for']) && !empty($criteria['for'])) {
            $query->where('for', $criteria['for']);
        }

        if (isset($criteria['created_at_from']) && !empty($criteria['created_at_from'])) {
            $query->whereDate('created_at', '>=', $criteria['created_at_from']);
        }

        if (isset($criteria['created_at_to']) && !empty($criteria['created_at_to'])) {
            $query->whereDate('created_at', '<=', $criteria['created_at_to']);
        }

        // Add sorting
        $sortBy = $criteria['sort_by'] ?? 'created_at';
        $sortOrder = $criteria['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Bulk delete codes
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

            Log::info('Bulk delete codes completed', [
                'ids' => $ids,
                'deleted_count' => $deleted
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete codes', [
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get codes by specific field
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
     * Count total codes
     *
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if code exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get latest codes
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * Duplicate a code
     *
     * @param Code $code
     * @return Code
     * @throws \Exception
     */
    public function duplicate(Code $code): Code
    {
        try {
            DB::beginTransaction();

            $data = $code->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);

            $newCode = $this->model->create($data);

            DB::commit();

            Log::info('Code duplicated successfully', [
                'original_id' => $code->id,
                'new_id' => $newCode->id
            ]);

            return $newCode;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating Code', [
                'id' => $code->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    public function generateUniqueCode()
    {
        $code = null ;
        do{
            $code = str_pad(random_int(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
        }while(Code::query()->where('code', $code)->exists());
        return $code;
    }
}
