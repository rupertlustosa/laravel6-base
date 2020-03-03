<?php
/**
 * @package    Services
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 19:01:44
 */

declare(strict_roles=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

    private function buildQuery(): Builder
    {

        $query = User::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('role_id'), function ($query, $roleId) {

            return $query->whereHas('roles', function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            });
        });

        $query->when(request('search'), function ($query, $search) {

            return $query->where(function ($q) use ($search) {
                $q->whereRaw('
					users.name LIKE ?
					OR users.email LIKE ?
					',
                    [
                        "%{$search}%",
                        "%{$search}%",
                    ]
                );
            });
        });

        $query->when(request('document_number'), function ($query, $search) {

            return $query->where('document_number', 'LIKE', '%' . $search . '%');
        });

        if (request('active') == '0') {

            $query->where('active', 0);
        }

        if (request('active') == '1') {

            $query->where('active', 1);
        }

        return $query->orderByDesc('id');
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function find(int $id): ?User
    {

        //return Cache::remember('User_find_' . $id, config('cache.cache_time'), function () use ($id) {
        return User::find($id);
        //});
    }

    public function create(array $data): User
    {

        return DB::transaction(function () use ($data) {

            $data['image'] = uploadWithCrop('image', 'users');

            /*if (isset($data["birth"])) {
                $data["birth"] = Carbon::createFromFormat('d/m/Y', $data["birth"]);
            }*/

            $model = new User();
            $model->fill($data);

            if (!empty($data["password"])) {
                $model->password = Hash::make($data["password"]);
            }
            $model->save();

            if (!array_key_exists('roles', $data)) {

                $data['roles'] = [];
            }

            $model->roles()->sync($data['roles']);

            return $model;
        });
    }

    public function update(array $data, User $model): User
    {

        if (!empty($data["password"])) {

            $model->password = Hash::make($data["password"]);
        }

        /*if (isset($data["birth"])) {
            $data["birth"] = Carbon::createFromFormat('d/m/Y', $data["birth"]);
        }*/

        $image = uploadWithCrop('image', 'users');

        if ($image !== null) {

            $data['image'] = $image;
        } elseif (request('delete_image')) {

            $data['image'] = null;
        }
        $model->fill($data);

        $model->save();

        if (!array_key_exists('roles', $data)) {

            $data['roles'] = [];
        }

        $model->roles()->sync($data['roles']);

        return $model;
    }

    public function profileUpdate(array $data, User $model): User
    {

        if (!empty($data["password"])) {
            $model->password = Hash::make($data["password"]);
        }

        $image = uploadWithCrop('image', 'users');

        if ($image !== null) {
            $data['image'] = $image;
        } elseif (request('delete_image')) {

            $data['image'] = null;
        }

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(User $model): ?bool
    {

        return $model->delete();
    }

    public function lists(): array
    {
        //return Cache::remember('User_lists', config('cache.cache_time'), function () {

        return User::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        //});
    }
}
