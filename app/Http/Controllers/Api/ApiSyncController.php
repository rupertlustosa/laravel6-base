<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OfferResource;
use App\Http\Resources\SaleResource;
use App\Services\OfferService;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\JsonResponse;

class ApiSyncController extends ApiBaseController
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

    }

    public function getSalesToPointing(SaleService $saleService): JsonResponse
    {

        try {

            $data = $saleService->getSalesToPointing();
            $groupBy = [];
            $sales = [];
            $groupByField = 'fuel_pump_nozzle';

            foreach ($data as $sale) {

                if (!in_array($sale->{$groupByField}, $groupBy)) {

                    $groupBy[] = $sale->{$groupByField};
                }

                $sales[$sale->{$groupByField}][] = $sale;
            }

            $dataToSend = [];

            foreach ($groupBy as $field) {

                $dataToSend[] = [
                    'name' => $field,
                    'sales' => SaleResource::collection($sales[$field]),
                ];
            }

            if (count($dataToSend)) {

                usort($dataToSend, function ($item1, $item2) {

                    return $item1['name'] <=> $item2['name'];
                });
            }

            return $this->sendResponse($dataToSend);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }
}
