<?php


namespace App\Components\Auth;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class AuthSigninDto extends DataTransferObject
{
    #[MapFrom('email')]
    public float $email;

    #[MapFrom('password')]
    public float $password;
}
