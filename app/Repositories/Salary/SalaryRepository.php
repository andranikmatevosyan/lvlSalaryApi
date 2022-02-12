<?php


namespace App\Repositories\Salary;


use App\Components\Salary\SalaryCreateDto;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SalaryRepository
{
    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return Builder|Model|null
     */
    public static function getSalaryByFieldsOne(int $worker_id, int $year, int $month): Model|Builder|null
    {
        return self::getSalaryByFieldsQuery($worker_id, $year, $month)->first();
    }

    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return bool
     */
    public static function getSalaryByFieldsCheck(int $worker_id, int $year, int $month): bool
    {
        return self::getSalaryByFieldsQuery($worker_id, $year, $month)->exists();
    }

    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return Builder
     */
    public static function getSalaryByFieldsQuery(int $worker_id, int $year, int $month): Builder
    {
        return Salary::query()->where([
            'worker_id' => $worker_id,
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * @param SalaryCreateDto $salaryCreateDto
     * @return Builder|Model
     */
    public static function createSalary(SalaryCreateDto $salaryCreateDto): Model|Builder
    {
        return Salary::query()->create($salaryCreateDto->all());
    }

    /**
     * @param SalaryCreateDto $salaryCreateDto
     * @return int
     */
    public static function updateSalaryByFields(SalaryCreateDto $salaryCreateDto): int
    {
        return Salary::query()->where(
            $salaryCreateDto->only('worker_id', 'year', 'month')->toArray()
        )->update($salaryCreateDto->only('month_days_norm', 'month_days_work', 'has_mzp', 'salary', 'norm_salary', 'net_salary', 'tax_ipn', 'tax_opv', 'tax_so', 'insurance_osms', 'insurance_vosms', 'currency')->toArray());
    }
}
