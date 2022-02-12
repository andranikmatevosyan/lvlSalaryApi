<?php


namespace App\Repositories\WorkerStatus;


use App\Components\WorkerStatus\WorkerStatusCreateDto;
use App\Models\WorkerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WorkerStatusRepository
{
    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return Model|Builder|null
     */
    public static function getWorkerStatusByFieldsOne(int $worker_id, int $year, int $month): Model|Builder|null
    {
        return self::getWorkerStatusByFieldsQuery($worker_id, $year, $month)->first();
    }

    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return bool
     */
    public static function getWorkerStatusByFieldsCheck(int $worker_id, int $year, int $month): bool
    {
        return self::getWorkerStatusByFieldsQuery($worker_id, $year, $month)->exists();
    }

    /**
     * @param int $worker_id
     * @param int $year
     * @param int $month
     * @return Builder
     */
    public static function getWorkerStatusByFieldsQuery(int $worker_id, int $year, int $month): Builder
    {
        return WorkerStatus::query()->where([
            'worker_id' => $worker_id,
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * @param WorkerStatusCreateDto $workerStatusCreateDto
     * @return Builder|Model
     */
    public static function createWorkerStatus(WorkerStatusCreateDto $workerStatusCreateDto): Model|Builder
    {
        return WorkerStatus::query()->create($workerStatusCreateDto->all());
    }

    /**
     * @param WorkerStatusCreateDto $workerStatusCreateDto
     * @return int
     */
    public static function updateWorkerStatusByFields(WorkerStatusCreateDto $workerStatusCreateDto): int
    {
        return WorkerStatus::query()->where(
            $workerStatusCreateDto->only('worker_id', 'year', 'month')->toArray()
        )->update($workerStatusCreateDto->only('is_retiree', 'is_handicapped', 'handicapped_group')->toArray());
    }
}
