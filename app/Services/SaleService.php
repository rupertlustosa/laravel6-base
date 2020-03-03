<?php
/**
 * @package    Services
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SaleService
{

    private function buildQuery(): Builder
    {

        $query = Sale::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('search'), function ($query, $search) {

            return $query->where('id', 'LIKE', '%' . $search . '%');
        });

        if (request('synced') == 'false') {

            $query->whereNull('synced_at');
        }

        if (request('synced') == 'true') {

            $query->whereNotNull('synced_at');
        }

        return $query->orderByDesc('id');
    }

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function find(int $id): ?Sale
    {

        //return Cache::remember('Sale_find_' . $id, config('cache.cache_time'), function () use ($id) {
        return Sale::find($id);
        //});
    }

    public function create(array $data): Sale
    {

        return DB::transaction(function () use ($data) {

            $model = new Sale();
            $model->fill($data);
            $model->save();

            return $model;
        });
    }

    public function update(array $data, Sale $model): Sale
    {

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(Sale $model): ?bool
    {

        return $model->delete();
    }

    public function lists(): array
    {
        //return Cache::remember('Sale_lists', config('cache.cache_time'), function () {

        return Sale::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        //});
    }
}
