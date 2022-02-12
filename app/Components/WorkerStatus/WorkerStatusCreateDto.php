<?php


namespace App\Components\WorkerStatus;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class WorkerStatusCreateDto extends DataTransferObject
{
    #[MapFrom(0)]
    public int $worker_id;

    #[MapFrom(1)]
    public int $year;

    #[MapFrom(2)]
    public int $month;

    #[MapFrom(3)]
    public string $is_retiree;

    #[MapFrom(4)]
    public string $is_handicapped;

    #[MapFrom(5)]
    public int $handicapped_group;
}
