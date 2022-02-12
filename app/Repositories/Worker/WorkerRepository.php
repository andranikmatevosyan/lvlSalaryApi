<?php


namespace App\Repositories\Worker;


use App\Components\Worker\WorkerCreateDto;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WorkerRepository
{
    /**
     * @param string $passport_id
     * @return Model|Builder|null
     */
    public static function getWorkerByPassportIdOne(string $passport_id): Model|Builder|null
    {
        return self::getWorkerByPassportIdQuery($passport_id)->first();
    }

    /**
     * @param string $passport_id
     * @return bool
     */
    public static function getWorkerByPassportIdCheck(string $passport_id): bool
    {
        return self::getWorkerByPassportIdQuery($passport_id)->exists();
    }

    /**
     * @param string $passport_id
     * @return Builder
     */
    public static function getWorkerByPassportIdQuery(string $passport_id): Builder
    {
        return Worker::query()->where(compact('passport_id'));
    }

    /**
     * @param WorkerCreateDto $workerCreateDto
     * @return Builder|Model
     */
    public static function createWorker(WorkerCreateDto $workerCreateDto): Model|Builder
    {
        return Worker::query()->create($workerCreateDto->toArray());
    }

    /**
     * @param int $worker_id
     * @return Model|Builder|null
     */
    public static function getWorkerWithRelationsOne(int $worker_id): Model|Builder|null
    {
        return Worker::query()->with(['worker_statuses_data', 'salaries_data'])->where([
            'id' => $worker_id
        ])->first();
    }
}
