<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Salary\CalculateRequest;
use App\Http\Requests\Salary\SaveRequest;
use App\Http\Resources\Salary\SalaryResource;
use App\Models\Salary;
use App\Models\Worker;
use App\Models\WorkerStatus;
use App\Services\Salary\SalaryService;
use App\traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class SalаryController extends BaseController
{
    /**
     * @param CalculateRequest $request
     * @param SalaryService $salaryService
     * @return JsonResponse
     */
    public function calculate(CalculateRequest $request, SalaryService $salaryService): JsonResponse
    {
        try {
            $salaryService->init($request->toArray());
            $response = $salaryService->getData();
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getFile());
        }

        return $this->sendResponse('Salary fetched successfully.', $response);
    }

    /**
     * @param SaveRequest $request
     * @param SalaryService $salaryService
     * @return JsonResponse
     */
    public function save(SaveRequest $request, SalaryService $salaryService): JsonResponse
    {
        DB::beginTransaction();

        try {
            $salaryService->init($request->toArray());
            $salary_data = $salaryService->getData();

            $worker_query = Worker::query()->where([
                'passport_id' => $request->get('passport_id')
            ]);

            if (!$worker_query->exists()):
                $passport_id = $request->get('passport_id');
                Worker::query()->create(compact('passport_id'));
            endif;

            $worker = Worker::query()->where([
                'passport_id' => $request->get('passport_id')
            ])->first();

            $worker_status_query = WorkerStatus::query()->where([
                'worker_id' => $worker->id,
                'year' => $request->get('year'),
                'month' => $request->get('month')
            ]);

            $worker_id = $worker->id;
            $year = $request->get('year');
            $month = $request->get('month');
            $is_retiree = $request->get('is_retiree');
            $is_handicapped = $request->get('is_handicapped');
            $handicapped_group = $request->get('handicapped_group');
            $worker_status_insert = compact('worker_id', 'year', 'month', 'is_retiree', 'is_handicapped', 'handicapped_group');
            $worker_status_update = compact('is_retiree', 'is_handicapped', 'handicapped_group');

            if (!$worker_status_query->exists()):
                WorkerStatus::query()->create($worker_status_insert);
            else:
                if ($request->get('update_if_exists') === 'yes'):
                    WorkerStatus::query()->where([
                        'id' => $worker_id
                    ])->update($worker_status_update);
                endif;
            endif;

            $salary_query = Salary::query()->where([
                'worker_id' => $worker->id,
                'year' => $request->get('year'),
                'month' => $request->get('month')
            ]);

            $worker_id = $worker->id;
            $year = $request->get('year');
            $month = $request->get('month');
            $month_days_norm = $request->get('month_days_norm');
            $month_days_work = $request->get('month_days_work');
            $has_mzp = $request->get('has_mzp');
            $salary = $salary_data['salary'];
            $norm_salary = $salary_data['norm_salary'];
            $net_salary = $salary_data['net_salary'];
            $tax_ipn = $salary_data['tax_ipn'];
            $tax_opv = $salary_data['tax_opv'];
            $tax_so = $salary_data['tax_so'];
            $insurance_osms = $salary_data['insurance_osms'];
            $insurance_vosms = $salary_data['insurance_vosms'];
            $currency = $salary_data['currency'];
            $salary_insert = compact('worker_id','year', 'month', 'month_days_norm', 'month_days_work', 'has_mzp', 'salary', 'norm_salary', 'net_salary', 'tax_ipn', 'tax_opv', 'tax_so', 'insurance_osms', 'insurance_vosms', 'currency');
            $salary_update = compact('month_days_norm', 'month_days_work', 'has_mzp', 'salary', 'norm_salary', 'net_salary', 'tax_ipn', 'tax_opv', 'tax_so', 'insurance_osms', 'insurance_vosms', 'currency');

            if (!$salary_query->exists()):
                Salary::query()->create($salary_insert);
            else:
                if ($request->get('update_if_exists') === 'yes'):
                    Salary::query()->where([
                        'id' => $worker_id
                    ])->update($salary_update);
                endif;
            endif;

            $response = Worker::query()->with(['worker_statuses_data', 'salaries_data'])->where([
                'id' => $worker->id
            ])->first();

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage(), $exception->getFile());
        }

        DB::commit();

        return $this->sendResponse('Salary saved successfully.', new SalaryResource($response));
    }
}
