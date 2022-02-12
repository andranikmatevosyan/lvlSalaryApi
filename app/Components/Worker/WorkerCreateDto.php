<?php


namespace App\Components\Worker;


use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class WorkerCreateDto extends DataTransferObject
{
    #[MapFrom(0)]
    public string $passport_id;
}
