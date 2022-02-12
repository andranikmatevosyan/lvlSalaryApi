<?php

namespace App\Http\Controllers\Api;

use App\Actions\Salary\SalaryAction;
use App\Components\Salary\SalaryCalculateDto;
use App\Components\Salary\SalarySaveDto;
use App\Http\Requests\Salary\CalculateRequest;
use App\Http\Requests\Salary\SaveRequest;
use App\Http\Resources\Salary\SalaryResource;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class SalаryController extends BaseController
{
    /**
     * @param CalculateRequest $request
     * @param SalaryAction $salaryAction
     * @return JsonResponse
     */
    public function calculate(CalculateRequest $request, SalaryAction $salaryAction): JsonResponse
    {
        try {
            $response = $salaryAction->calculateAction(new SalaryCalculateDto($request->toArray()));
        } catch (Exception $exception) {
            return $this->response500([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile()
            ]);
        }

        return $this->response202($response);
    }

    /**
     * @param SaveRequest $request
     * @param SalaryAction $salaryAction
     * @return JsonResponse
     */
    public function save(SaveRequest $request, SalaryAction $salaryAction): JsonResponse
    {
        DB::beginTransaction();

        try {
            $salaryCalculateDto = new SalaryCalculateDto($request->toArray());

            $response = $salaryAction->saveAction(new SalarySaveDto([
                $request->get('passport_id'),
                $request->get('update_if_exists'),
                $salaryCalculateDto->toArray()
            ]));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->response500([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile()
            ]);
        }

        DB::commit();

        return $this->response201(new SalaryResource($response));
    }
}
