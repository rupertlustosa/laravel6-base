<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\Type;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class PassportController extends ApiBaseController
{

    /**
     * Handles Registration Request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function register(UserService $userService)
    {
        $storeRequest = new UserStoreRequest();
        $validator = Validator::make(request()->all(), $storeRequest->rulesRegisterApi(), $storeRequest->messages());

        if ($validator->fails()) {

            return $this->sendBadRequest('Validation Error.', array_values($validator->errors()->toArray()));
        }

        request()->request->set('type', Type::CLIENT);

        $user = $userService->create(request()->all());

        $token = $user->createToken('AppName')->accessToken;

        return response()->json(['token' => $token, 'user' => new UserResource($user->fresh())], 200);
    }


    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {

        $storeRequest = new UserStoreRequest();
        $rules = $storeRequest->rules();

        $rules = [
            'email' => str_replace('nullable', 'required', $rules['email']),
            'password' => str_replace('confirmed', '', $rules['password']),
        ];

        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, $rules, $storeRequest->messages());

        if ($validator->fails()) {

            #return $this->sendBadRequest('Validation Error.', $validator->errors()->toArray());
            return $this->sendBadRequest('Validation Error.', array_values($validator->errors()->toArray()));
        }

        if (auth()->attempt($data)) {

            $user = auth()->user();
            #$user = new UserResource(auth()->user());
            $token = auth()->user()->createToken('AppName')->accessToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);

        } else {

            #return response()->json(['error' => 'Unauthorized'], 401);
            return $this->sendBadRequest('Validation Error.', [['Usuário ou senha inválidos']]);
        }
    }


    public function unAuthenticated()
    {

        return $this->sendUnauthorized();
    }
}
