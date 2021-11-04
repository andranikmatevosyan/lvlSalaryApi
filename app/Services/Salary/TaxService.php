<?php

namespace App\Services\Salary;


class TaxService implements MainInterface, TaxInterface
{
    private $request = [];
    private $salaryService;
    private $normSalary;

    public function __construct($request, $salaryService = null)
    {
        $this->setRequest($request);

        if ($salaryService):
            $this->salaryService = $salaryService;
        else:
            $this->salaryService = new SalaryService();
            $this->salaryService->init($this->request);
        endif;

        $this->normSalary = $this->salaryService->getNormSalary();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $tax_ipn = $this->getIpnTax();
        $tax_opv = $this->getOpvTax();
        $tax_so = $this->getSoTax();
        $insurance_osms = $this->getOsmsInsurance();
        $insurance_vosms = $this->getVosmsInsurance();

        return compact('tax_ipn','tax_opv', 'tax_so', 'insurance_osms', 'insurance_vosms');
    }

    /**
     * @return float
     */
    public function getIpnTax(): float
    {
        $response = 0;
        $has_tax = $this->hasTaxType();

        if ($has_tax['has_ipn']):
            $opv_tax = $this->getOpvTax();
            $deduction_mzp = $this->getMzpDeduction();
            $insurance_vosms = $this->getVosmsInsurance();
            $adjustment = $this->getAdjustment();
            $response = ($this->normSalary - $opv_tax - $deduction_mzp - $insurance_vosms - $adjustment) * 10 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getOpvTax(): float
    {
        $response = 0;
        $has_tax = $this->hasTaxType();

        if ($has_tax['has_opv']):
            $response = $this->normSalary * 10 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getSoTax(): float
    {
        $response = 0;
        $has_tax = $this->hasTaxType();

        if ($has_tax['has_so']):
            $opv_tax = $this->getOpvTax();
            $response = ($this->normSalary - $opv_tax) * 3.5 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getOsmsInsurance(): float
    {
        $response = 0;
        $has_tax = $this->hasTaxType();

        if ($has_tax['has_osms']):
            $response = $this->normSalary * 2 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getVosmsInsurance(): float
    {
        $response = 0;
        $has_tax = $this->hasTaxType();

        if ($has_tax['has_vosms']):
            $response = $this->normSalary * 2 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getMzpDeduction(): float
    {
        $response = 0;

        if ($this->request['has_mzp'] === 'yes'):
            $response = $this->salaryService->getMrpSalary();
        endif;

        return floatval($response);
    }

    /**
     * @return float
     */
    public function getAdjustment(): float
    {
        $response = 0;
        $salary_mrp = $this->salaryService->getMrpSalary();

        if ($this->request['salary'] < 25 * $salary_mrp):
            $response = ($this->normSalary - $this->getOpvTax() - $this->getMzpDeduction() - $this->getVosmsInsurance()) * 90 / 100;
        endif;

        return floatval($response);
    }

    /**
     * @return array
     */
    public function hasTaxType(): array
    {
        $salary_mrp = $this->salaryService->getMrpSalary();

        switch (true):
            case ($this->request['is_retiree'] === 'yes'):
                $has_ipn = true;
                $has_opv = false;
                $has_osms = false;
                $has_vosms = false;
                $has_so = false;

                if ($this->request['is_handicapped'] === 'yes'):
                    $has_ipn = false;
                endif;

                break;
            case ($this->request['is_handicapped'] === 'yes' && in_array($this->request['handicapped_group'], [1, 2, 3])):
                $has_ipn = false;
                $has_opv = false;
                $has_osms = false;
                $has_vosms = false;
                $has_so = false;

                if (in_array($this->request['handicapped_group'], [1, 2])):
                    $has_so = true;
                endif;

                if ($this->request['handicapped_group'] == 3):
                    $has_opv = true;
                    $has_so = true;
                endif;

                if ($this->request['salary'] > 882 * $salary_mrp):
                    $has_ipn = true;
                endif;

                if ($this->request['is_retiree'] === 'yes'):
                    $has_ipn = false;
                    $has_opv = false;
                    $has_osms = false;
                    $has_vosms = false;
                    $has_so = false;
                endif;

                break;
            default:
                $has_ipn = true;
                $has_opv = true;
                $has_osms = true;
                $has_vosms = true;
                $has_so = true;
                break;
        endswitch;

        return compact('has_ipn', 'has_opv', 'has_osms', 'has_vosms', 'has_so');
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
