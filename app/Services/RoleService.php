<?php
/**
 * @package    Services
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 18:59:03
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{

    private function buildQuery(): Builder
    {

        $query = Role::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('search'), function ($query, $search) {

            return $query->where('id', 'LIKE', '%' . $search . '%');
        });

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

    public function find(int $id): ?Role
    {

        //return Cache::remember('Role_find_' . $id, config('cache.cache_time'), function () use ($id) {
        return Role::find($id);
        //});
    }

    public function create(array $data): Role
    {

        return DB::transaction(function () use ($data) {

            $model = new Role();
            $model->fill($data);
            #$model->user_creator_id = \Auth::id();
            #$model->user_updater_id = \Auth::id();
            $model->save();

            return $model;
        });
    }

    public function update(array $data, Role $model): Role
    {

        $model->fill($data);
        #$model->user_updater_id = \Auth::id();
        $model->save();

        return $model;
    }

    public function delete(Role $model): ?bool
    {
        #$model->user_eraser_id = \Auth::id();
        #$model->timestamps = false;
        #$model->save();

        return $model->delete();
    }

    public function lists(): array
    {
        //return Cache::remember('Role_lists', config('cache.cache_time'), function () {

        return Role::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        //});
    }
}
