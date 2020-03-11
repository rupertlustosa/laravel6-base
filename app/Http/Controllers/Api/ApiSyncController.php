<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OfferResource;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\OfferService;
use App\Services\SaleService;
use App\Services\SettingService;
use Carbon\Carbon;
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

            $settings = (new SettingService())->settings();

            $numberOfNozzles = $settings->where('key', 'NOZZLE_NUMBER')->first();

            if ($numberOfNozzles->value) {

                for ($x = 1; $x <= $numberOfNozzles->value; $x++) {

                    $groupBy[] = $x;
                }
            }

            //dd($settings->where('key', 'NOZZLE_NUMBER')->first());

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
                    'sales' => isset($sales[$field]) ? SaleResource::collection($sales[$field]) : [],
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

    /**
     * Update.
     *
     * @param Sale $sale
     * @return JsonResponse
     */
    public function save(Sale $sale)
    {
        try {

            if (!empty($sale->document_number)) {

                return $this->sendUnauthorized();
            }

            $birth = request('birth') ?? null;
            if ($birth) {

                try {

                    $birth = Carbon::createFromFormat('d/m/y', $birth)->format('Y-m-d');
                } catch (Exception $exception) {
                    $birth = null;
                }
            }

            $sale->document_number = request('document_number');
            $sale->name = request('name') ?? null;
            $sale->birth = $birth;
            $sale->phone = request('phone') ?? null;
            $sale->save();

            return $this->sendSimpleJson();

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

    /**
     * Get settings.
     *
     * @param SettingService $settingService
     * @return JsonResponse
     */
    public function settings(SettingService $settingService)
    {
        try {

            return $this->sendSimpleJson($settingService->all()->pluck('value', 'key')->toArray());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

}
