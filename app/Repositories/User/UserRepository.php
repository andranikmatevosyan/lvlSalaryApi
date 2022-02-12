<?php

namespace App\Repositories\User;

use App\Components\Auth\AuthSignupDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    public static function createUser(AuthSignupDto $authSignupDto): Model|Builder
    {
        return User::query()->create($authSignupDto->toArray());
    }
}
