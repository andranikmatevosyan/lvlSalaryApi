<?php


namespace App\Components\Salary;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class SalaryCalculateDto extends DataTransferObject
{
    #[MapFrom('salary')]
    public float $salary;

    #[MapFrom('month_days_norm')]
    public int $month_days_norm;

    #[MapFrom('month_days_work')]
    public int $month_days_work;

    #[MapFrom('has_mzp')]
    public string $has_mzp;

    #[MapFrom('year')]
    public int $year;

    #[MapFrom('month')]
    public int $month;

    #[MapFrom('is_retiree')]
    public string $is_retiree;

    #[MapFrom('is_handicapped')]
    public string $is_handicapped;

    #[MapFrom('handicapped_group')]
    public int $handicapped_group;
}
