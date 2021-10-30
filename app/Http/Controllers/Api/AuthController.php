<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        if (!Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])):
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        endif;

        $authUser = Auth::user();
        $success['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;
        $success['name'] = $authUser->name;

        return $this->sendResponse($success, 'User signed in');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()):
            return $this->sendError('Error validation', $validator->errors());
        endif;

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::query()->create($input);
        $success['token'] = $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User created successfully.');
    }
}
