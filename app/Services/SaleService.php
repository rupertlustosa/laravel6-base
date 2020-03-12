<?php
/**
 * @package    Services
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Services;

use App\Models\Sale;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class SaleService
{

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

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

    public function paginateSynced(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()
            ->whereNotNull('synced_at')
            ->paginate($limit);
    }

    public function paginateNotSynced(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()
            ->whereNull('synced_at')
            ->whereNotNull('document_number')
            ->paginate($limit);
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function getSalesToPointing(): Collection
    {

        return $this->buildQuery()
            ->whereNull('document_number')
            //->where('date', '>=', now()->subMinutes(10))
            ->get();
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

    public function syncToWeb(Sale $sale)
    {

        if ($sale->synced_at) {

            $response = new stdClass();
            $response->success = false;
            $response->message = 'Já sincronizada!';

            return $response;
        }
        $settings = (new SettingService())->settings();
        $apiUrl = $settings->where('key', 'API_URL')->first();

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $apiUrl->value,
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);

        $promise = $client->postAsync('/api/sync/sale/pointing/' . $sale->sale, ['json' => $sale->toArray()])
            //$promise = $client->postAsync('/api/sync/sale/pointing/' . $sale->sale, $sale->toArray())
            ->then(
                function (ResponseInterface $res) {
                    $response = json_decode($res->getBody()->getContents());

                    return $response;
                },
                function (RequestException $e) {
                    $response = new stdClass();
                    $response->success = false;
                    $response->message = $e->getMessage();

                    return $response;
                }
            );

        $response = $promise->wait();

        if (isset($response->success) && $response->success == true) {

            $sale->synced_at = now();
            $sale->save();
        }

        return $response;
    }
}
