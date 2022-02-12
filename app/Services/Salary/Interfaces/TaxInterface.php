<?php

namespace App\Services\Salary\Interfaces;


interface TaxInterface
{
    public function getIpnTax(): float;
    public function getOpvTax(): float;
    public function getSoTax(): float;
    public function getOsmsInsurance(): float;
    public function getVosmsInsurance(): float;
    public function getMzpDeduction(): float;
    public function getAdjustment(): float;
    public function hasTaxType(): array;
}
