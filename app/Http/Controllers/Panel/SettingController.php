<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       10/03/2020 10:50:31
 */

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Models\Setting;
use App\Services\SettingService;
use App\Traits\LogActivity;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use JsValidator;

class SettingController extends ApiBaseController
{
    use LogActivity;

    private $service;
    private $label;

    public function __construct(SettingService $service)
    {

        $this->service = $service;
        $this->label = 'Configurações';
    }

    public function index(): View
    {

        $this->log(__METHOD__);

        $this->authorize('viewAny', Setting::class);

        $data = $this->service->paginate(20);

        return view('panel.settings.index')
            ->with([
                'data' => $data,
                'label' => $this->label,
            ]);
    }

    public function create(): View
    {

        $this->log(__METHOD__);

        $this->authorize('create', Setting::class);

        $validatorRequest = new SettingStoreRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.settings.form')
            ->with([
                'validator' => $validator,
                'label' => $this->label,
            ]);
    }

    public function store(SettingStoreRequest $settingStoreRequest)
    {

        $this->service->create($settingStoreRequest->all());

        return redirect()->route('settings.' . request('routeTo'))
            ->with([
                'message' => 'Criado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function edit(Setting $setting): View
    {

        $this->log(__METHOD__);

        $this->authorize('update', $setting);

        $validatorRequest = new SettingUpdateRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.settings.form')
            ->with([
                'item' => $setting,
                'label' => $this->label,
                'validator' => $validator,
            ]);
    }

    public function update(SettingUpdateRequest $request, Setting $setting): RedirectResponse
    {

        $this->log(__METHOD__);

        $this->service->update($request->all(), $setting);

        return redirect()->route('settings.index')
            ->with([
                'message' => 'Atualizado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function destroy(Setting $setting): JsonResponse
    {

        $this->log(__METHOD__);

        try {

            if (!Auth::user()->can('delete', $setting)) {

                return $this->sendUnauthorized();
            }

            $this->service->delete($setting);

            return $this->sendResponse([]);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    public function show(Setting $setting): JsonResponse
    {

        $this->log(__METHOD__);
        $this->authorize('update', $setting);

        return response()->json($setting, 200, [], JSON_PRETTY_PRINT);
    }
}
