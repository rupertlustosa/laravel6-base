<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 18:59:03
 */

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Services\RoleService;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use JsValidator;

class RoleController extends ApiBaseController
{
    use LogActivity;

    private $service;
    private $label;

    public function __construct(RoleService $service)
    {

        $this->service = $service;
        $this->label = 'Perfis';
    }

    public function index(): View
    {

        $this->log(__METHOD__);

        $this->authorize('viewAny', Role::class);

        $data = $this->service->paginate(20);

        return view('panel.roles.index')
            ->with([
                'data' => $data,
                'label' => $this->label,
            ]);
    }

    public function create(): View
    {

        $this->log(__METHOD__);

        $this->authorize('create', Role::class);

        $validatorRequest = new RoleStoreRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.roles.form')
            ->with([
                'validator' => $validator,
                'label' => $this->label,
            ]);
    }

    public function store(RoleStoreRequest $roleStoreRequest)
    {

        $this->service->create($roleStoreRequest->all());

        return redirect()->route('roles.' . request('routeTo'))
            ->with([
                'message' => 'Criado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function edit(Role $role): View
    {

        $this->log(__METHOD__);

        $this->authorize('update', $role);

        $validatorRequest = new RoleUpdateRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.roles.form')
            ->with([
                'item' => $role,
                'label' => $this->label,
                'validator' => $validator,
            ]);
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {

        $this->log(__METHOD__);

        $this->service->update($request->all(), $role);

        return redirect()->route('roles.index')
            ->with([
                'message' => 'Atualizado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function destroy(Role $role): JsonResponse
    {

        $this->log(__METHOD__);

        try {

            if (!\Auth::user()->can('delete', $role)) {

                return $this->sendUnauthorized();
            }

            $this->service->delete($role);

            return $this->sendResponse([]);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    public function show(Role $role): JsonResponse
    {

        $this->log(__METHOD__);
        $this->authorize('update', $role);

        return response()->json($role, 200, [], JSON_PRETTY_PRINT);
    }
}
