<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\SaleStoreRequest;
use App\Http\Requests\SaleUpdateRequest;
use App\Models\Sale;
use App\Services\SaleService;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use JsValidator;

class SaleController extends ApiBaseController
{
    use LogActivity;

    private $service;
    private $label;

    public function __construct(SaleService $service)
    {

        $this->service = $service;
        $this->label = 'Vendas';
    }

    public function index(): View
    {

        $this->log(__METHOD__);

        $this->authorize('viewAny', Sale::class);

        $data = $this->service->paginate(20);

        return view('panel.sales.index')
            ->with([
                'data' => $data,
                'label' => $this->label,
            ]);
    }

    public function create(): View
    {

        $this->log(__METHOD__);

        $this->authorize('create', Sale::class);

        $validatorRequest = new SaleStoreRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.sales.form')
            ->with([
                'validator' => $validator,
                'label' => $this->label,
            ]);
    }

    public function store(SaleStoreRequest $saleStoreRequest)
    {

        $this->service->create($saleStoreRequest->all());

        return redirect()->route('sales.' . request('routeTo'))
            ->with([
                'message' => 'Criado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function edit(Sale $sale): View
    {

        $this->log(__METHOD__);

        $this->authorize('update', $sale);

        $validatorRequest = new SaleUpdateRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.sales.form')
            ->with([
                'item' => $sale,
                'label' => $this->label,
                'validator' => $validator,
            ]);
    }

    public function update(SaleUpdateRequest $request, Sale $sale): RedirectResponse
    {

        $this->log(__METHOD__);

        $this->service->update($request->all(), $sale);

        return redirect()->route('sales.index')
            ->with([
                'message' => 'Atualizado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function destroy(Sale $sale): JsonResponse
    {

        $this->log(__METHOD__);

        try {

            if (!\Auth::user()->can('delete', $sale)) {

                return $this->sendUnauthorized();
            }

            $this->service->delete($sale);

            return $this->sendResponse([]);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    public function show(Sale $sale): JsonResponse
    {

        $this->log(__METHOD__);
        $this->authorize('update', $sale);

        return response()->json($sale, 200, [], JSON_PRETTY_PRINT);
    }
}
