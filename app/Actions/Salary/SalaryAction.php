<?php


namespace App\Actions\Salary;


use App\Components\Salary\SalaryCalculateDto;
use App\Components\Salary\SalaryCreateDto;
use App\Components\Salary\SalarySaveDto;
use App\Components\Worker\WorkerCreateDto;
use App\Components\WorkerStatus\WorkerStatusCreateDto;
use App\Helpers\Constant;
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\Worker\WorkerRepository;
use App\Repositories\WorkerStatus\WorkerStatusRepository;
use App\Services\Salary\SalaryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SalaryAction
{
    private SalaryService $salaryService;

    /**
     * SalaryAction constructor.
     * @param SalaryService $salaryService
     */
    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    /**
     * @param SalaryCalculateDto $salaryCalculateDto
     * @return array
     */
    public function calculateAction(SalaryCalculateDto $salaryCalculateDto): array
    {
        $this->salaryService->init($salaryCalculateDto->toArray());
        return $this->salaryService->getData();
    }

    /**
     * @param SalarySaveDto $salarySaveDto
     * @return Model|Builder|null
     * @throws UnknownProperties
     */
    public function saveAction(SalarySaveDto $salarySaveDto): Model|Builder|null
    {
        $salaryData = $this->calculateAction($salarySaveDto->salaryCalculateDto);

        $worker = $this->saveWorkerIfNotExists(new WorkerCreateDto([
            $salarySaveDto->passport_id
        ]));

        $this->upsertWorkerStatus(new WorkerStatusCreateDto([
            $worker->id,
            $salarySaveDto->salaryCalculateDto->year,
            $salarySaveDto->salaryCalculateDto->month,
            $salarySaveDto->salaryCalculateDto->is_retiree,
            $salarySaveDto->salaryCalculateDto->is_handicapped,
            $salarySaveDto->salaryCalculateDto->handicapped_group
        ]), $salarySaveDto->update_if_exists);

        $this->upsertSalary(new SalaryCreateDto([
            $worker->id,
            $salarySaveDto->salaryCalculateDto->year,
            $salarySaveDto->salaryCalculateDto->month,
            $salarySaveDto->salaryCalculateDto->month_days_norm,
            $salarySaveDto->salaryCalculateDto->month_days_work,
            $salarySaveDto->salaryCalculateDto->has_mzp,
            $salaryData['salary'],
            $salaryData['norm_salary'],
            $salaryData['net_salary'],
            $salaryData['tax_ipn'],
            $salaryData['tax_opv'],
            $salaryData['tax_so'],
            $salaryData['insurance_osms'],
            $salaryData['insurance_vosms'],
            $salaryData['currency']
        ]), $salarySaveDto->update_if_exists);

        return WorkerRepository::getWorkerWithRelationsOne($worker->id);
    }

    /**
     * @param WorkerCreateDto $workerCreateDto
     * @return Builder|Model|null
     */
    public function saveWorkerIfNotExists(WorkerCreateDto $workerCreateDto): Model|Builder|null
    {
        if (!WorkerRepository::getWorkerByPassportIdCheck($workerCreateDto->passport_id)):
            WorkerRepository::createWorker($workerCreateDto);
        endif;

        return WorkerRepository::getWorkerByPassportIdOne($workerCreateDto->passport_id);
    }

    /**
     * @param WorkerStatusCreateDto $workerStatusCreateDto
     * @param string $update_if_exists
     * @return Builder|Model|null
     */
    public function upsertWorkerStatus(WorkerStatusCreateDto $workerStatusCreateDto, string $update_if_exists = 'yes'): Model|Builder|null
    {
        if (!WorkerStatusRepository::getWorkerStatusByFieldsCheck($workerStatusCreateDto->worker_id, $workerStatusCreateDto->year, $workerStatusCreateDto->month)):
            WorkerStatusRepository::createWorkerStatus($workerStatusCreateDto);
        else:
            if ($update_if_exists === Constant::YES):
                WorkerStatusRepository::updateWorkerStatusByFields($workerStatusCreateDto);
            endif;
        endif;

        return WorkerStatusRepository::getWorkerStatusByFieldsOne($workerStatusCreateDto->worker_id, $workerStatusCreateDto->year, $workerStatusCreateDto->month);
    }

    /**
     * @param SalaryCreateDto $salaryCreateDto
     * @param string $update_if_exists
     * @return Builder|Model|null
     */
    public function upsertSalary(SalaryCreateDto $salaryCreateDto, string $update_if_exists = Constant::YES): Model|Builder|null
    {
        if (!SalaryRepository::getSalaryByFieldsCheck($salaryCreateDto->worker_id, $salaryCreateDto->year, $salaryCreateDto->month)):
            SalaryRepository::createSalary($salaryCreateDto);
        else:
            if ($update_if_exists === Constant::YES):
                SalaryRepository::updateSalaryByFields($salaryCreateDto);
            endif;
        endif;

        return SalaryRepository::getSalaryByFieldsOne($salaryCreateDto->worker_id, $salaryCreateDto->year, $salaryCreateDto->month);
    }
}
