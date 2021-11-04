<?php

namespace App\Services\Salary;


class SalaryService implements MainInterface, SalaryInterface
{
    private $request = [];
    private $normSalary;
    private $mrpSalary;
    private $taxService;
    private $currency;

    public function init($request, $currency = null)
    {
        $this->setRequest($request);
        $this->seCurrency($currency);
        $this->setNormSalary();
        $this->setMrpSalary();
        $this->taxService = new TaxService($request, $this);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $salary = floatval($this->request['salary']);
        $norm_salary = $this->normSalary;
        $net_salary = $this->getNetSalary();
        $taxes = $this->taxService->getData($this->request);
        $currency = $this->getCurrency();

        return array_merge(
            compact('salary', 'norm_salary', 'net_salary'),
            $taxes,
            compact('currency')
        );
    }

    /**
     * @return float
     */
    public function getNetSalary(): float
    {
        $tax_data = $this->taxService->getData($this->request);
        $response = $this->normSalary - $tax_data['tax_ipn'] - $tax_data['tax_opv'] - $tax_data['tax_so'] - $tax_data['insurance_osms'] - $tax_data['insurance_vosms'];

        return floatval($response);
    }

    public function setMrpSalary()
    {
        $this->mrpSalary = 42500;
    }

    /**
     * @return float
     */
    public function getMrpSalary(): float
    {
        return floatval($this->mrpSalary);
    }

    /**
     * @param string|null $currency
     */
    public function seCurrency(string $currency = null)
    {
        $this->currency = $currency ?? 'KZT';
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function setNormSalary()
    {
        $this->normSalary = floatval($this->request['salary'] * $this->request['month_days_work'] / $this->request['month_days_norm']);
    }

    /**
     * @return mixed
     */
    public function getNormSalary()
    {
        return $this->normSalary;
    }

    /**
     * @param array $request
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
