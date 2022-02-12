<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\AuthAction;
use App\Components\Auth\AuthSigninDto;
use App\Components\Auth\AuthSignupDto;
use App\Http\Requests\Auth\SigninRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    /**
     * @param SigninRequest $request
     * @param AuthAction $authAction
     * @return JsonResponse
     */
    public function signIn(SigninRequest $request, AuthAction $authAction): JsonResponse
    {
        try {
            $response = $authAction->signInAction(new AuthSigninDto($request->toArray()));

            if ($response['type'] === 'error'):
                return $this->response401('Unauthorised.');
            endif;
        } catch (Exception $exception) {
            return $this->response500([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile()
            ]);
        }

        return $this->response200($response);
    }

    /**
     * @param SignupRequest $request
     * @param AuthAction $authAction
     * @return JsonResponse
     */
    public function signUp(SignupRequest $request, AuthAction $authAction): JsonResponse
    {
        try {
            $request_array = $request->all();
            $request_array['password'] = bcrypt($request_array['password']);
            $response = $authAction->signUpAction(new AuthSignupDto($request_array));
        } catch (Exception $exception) {
            return $this->response500([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile()
            ]);
        }

        return $this->response201($response);
    }
}
