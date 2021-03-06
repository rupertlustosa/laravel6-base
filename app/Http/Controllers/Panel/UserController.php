<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       09/12/2019 10:25:33
 */

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use App\Traits\ImageCrop;
use App\Traits\LogActivity;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use JsValidator;

class UserController extends ApiBaseController
{
    use LogActivity, ImageCrop;

    private $service;
    private $label;
    private $cropModule;

    public function __construct(UserService $service)
    {

        $this->service = $service;
        $this->label = 'Usuários';
        $this->cropModule = "users";
    }

    public function index(RoleService $roleService): View
    {

        $this->log(__METHOD__);

        $this->authorize('viewAny', User::class);

        $data = $this->service->paginate(20);

        return view('panel.users.index')
            ->with([
                'data' => $data,
                'label' => $this->label,
            ]);
    }

    public function create(RoleService $roleService): View
    {

        $this->log(__METHOD__);

        $this->authorize('create', User::class);

        $validatorRequest = new UserStoreRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());
        $roleOptions = $roleService->lists();

        return view('panel.users.form')
            ->with([
                'validator' => $validator,
                'label' => $this->label,
                'roleOptions' => $roleOptions,
            ]);
    }

    public function store(UserStoreRequest $userStoreRequest)
    {

        $this->service->create($userStoreRequest->all());

        return redirect()->route('users.' . request('routeTo'))
            ->with([
                'message' => 'Criado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function edit(User $user, RoleService $roleService): View
    {

        $this->log(__METHOD__);

        $this->authorize('update', $user);

        $validatorRequest = new UserUpdateRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());
        $roleOptions = $roleService->lists();

        return view('panel.users.form')
            ->with([
                'item' => $user,
                'label' => $this->label,
                'validator' => $validator,
                'roleOptions' => $roleOptions,
            ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {

        $this->log(__METHOD__);

        $this->service->update($request->all(), $user);

        return redirect()->route('users.index')
            ->with([
                'message' => 'Atualizado com sucesso',
                'messageType' => 's',
            ]);
    }

    public function destroy(User $user): JsonResponse
    {

        $this->log(__METHOD__);

        try {

            if (!Auth::user()->can('delete', $user)) {

                return $this->sendUnauthorized();
            }

            $this->service->delete($user);

            return $this->sendResponse([]);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    public function show(User $user): JsonResponse
    {

        $this->log(__METHOD__);
        $this->authorize('update', $user);

        return response()->json($user, 200, [], JSON_PRETTY_PRINT);
    }

    public function find()
    {
        return $this->service->findList();
    }

    public function profile(): View
    {
        $this->log(__METHOD__);

        $user = \Illuminate\Support\Facades\Auth::user();
        $this->authorize('profile', $user);

        $validatorRequest = new UserProfileRequest();
        $validator = JsValidator::make($validatorRequest->rules(), $validatorRequest->messages());

        return view('panel.users.profile.form')
            ->with([
                'item' => $user,
                'label' => $this->label,
                'validator' => $validator,
            ]);
    }

    public function profileUpdate(UserProfileRequest $request): RedirectResponse
    {

        $this->log(__METHOD__);
        $user = Auth::user();

        $this->service->profileUpdate($request->all(), $user);

        return redirect()->route('users.profile')
            ->with([
                'message' => 'Atualizado com sucesso',
                'messageType' => 's',
            ]);
    }

}
