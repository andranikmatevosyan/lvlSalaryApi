<?php

namespace App\Components\Salary;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class SalarySaveDto extends DataTransferObject
{
    #[MapFrom(0)]
    public string $passport_id;

    #[MapFrom(1)]
    public string $update_if_exists;

    #[MapFrom(2)]
    public SalaryCalculateDto $salaryCalculateDto;
}
