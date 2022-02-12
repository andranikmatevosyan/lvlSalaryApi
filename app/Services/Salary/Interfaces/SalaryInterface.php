<?php


namespace App\Services\Salary\Interfaces;


interface SalaryInterface
{
    public function init($request, $currency = null);
    public function getNetSalary(): float;
    public function getMrpSalary(): float;
    public function setMrpSalary();
    public function seCurrency(string $currency = null);
    public function getCurrency();
    public function setNormSalary();
    public function getNormSalary();
}
