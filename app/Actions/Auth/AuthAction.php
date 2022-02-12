<?php


namespace App\Actions\Auth;


use App\Components\Auth\AuthSigninDto;
use App\Components\Auth\AuthSignupDto;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthAction
{
    /**
     * @param AuthSigninDto $authSigninDto
     * @return array
     */
    public function signInAction(AuthSigninDto $authSigninDto): array
    {
        if (!Auth::attempt(['email' => $authSigninDto->email, 'password' => $authSigninDto->password])):
            $type = 'error';
            return compact('type');
        endif;

        $type = 'success';
        $token = Auth::user()->createToken('MyAuthApp')->plainTextToken;
        $name = Auth::user()->name;

        return compact('type', 'token', 'name');
    }

    /**
     * @param AuthSignupDto $authSignupDto
     * @return array
     */
    public function signUpAction(AuthSignupDto $authSignupDto): array
    {
        $user = UserRepository::createUser($authSignupDto);
        $token = $user->createToken('MyAuthApp')->plainTextToken;
        $name = $user->name;

        return compact('token', 'name');
    }
}
