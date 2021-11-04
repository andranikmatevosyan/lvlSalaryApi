<?php


namespace App\Services\Salary;


interface SalaryInterface
{
    public function getNetSalary(): float;
    public function getMrpSalary(): float;
    public function setMrpSalary();
}
