<?php
/**
 * @package    Services
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       10/03/2020 10:50:31
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SettingService
{

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

    private function buildQuery(): Builder
    {

        $query = Setting::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('search'), function ($query, $search) {

            return $query->where('id', 'LIKE', '%' . $search . '%');
        });

        return $query->orderByDesc('id');
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function find(int $id): ?Setting
    {

        //return Cache::remember('Setting_find_' . $id, config('cache.cache_time'), function () use ($id) {
        return Setting::find($id);
        //});
    }

    public function create(array $data): Setting
    {

        return DB::transaction(function () use ($data) {

            $model = new Setting();
            $model->fill($data);
            $model->save();

            return $model;
        });
    }

    public function update(array $data, Setting $model): Setting
    {

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(Setting $model): ?bool
    {

        return $model->delete();
    }

    public function lists(): array
    {
        //return Cache::remember('Setting_lists', config('cache.cache_time'), function () {

        return Setting::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        //});
    }
}
