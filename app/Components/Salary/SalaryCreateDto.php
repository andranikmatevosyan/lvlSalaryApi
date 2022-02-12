<?php


namespace App\Components\Salary;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class SalaryCreateDto extends DataTransferObject
{
    #[MapFrom(0)]
    public int $worker_id;

    #[MapFrom(1)]
    public int $year;

    #[MapFrom(2)]
    public int $month;

    #[MapFrom(3)]
    public int $month_days_norm;

    #[MapFrom(4)]
    public int $month_days_work;

    #[MapFrom(5)]
    public string $has_mzp;

    #[MapFrom(6)]
    public float $salary;

    #[MapFrom(7)]
    public float $norm_salary;

    #[MapFrom(8)]
    public float $net_salary;

    #[MapFrom(9)]
    public float $tax_ipn;

    #[MapFrom(10)]
    public float $tax_opv;

    #[MapFrom(11)]
    public float $tax_so;

    #[MapFrom(12)]
    public float $insurance_osms;

    #[MapFrom(13)]
    public float $insurance_vosms;

    #[MapFrom(14)]
    public string $currency;
}
