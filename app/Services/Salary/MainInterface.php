<?php

namespace App\Services\Salary;


interface MainInterface
{
    public function getData(): array;
    public function setRequest(array $request);
    public function getRequest(): array;
}
