<?php


namespace App\Components\Auth;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class AuthSignupDto extends DataTransferObject
{
    #[MapFrom('name')]
    public float $name;

    #[MapFrom('email')]
    public float $email;

    #[MapFrom('password')]
    public float $password;
}
