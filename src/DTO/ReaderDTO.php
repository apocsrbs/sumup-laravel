<?php

namespace Sumup\Laravel\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Sumup\Laravel\DTO\DeviceDTO;

class ReaderDTO extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public DeviceDTO $device,
        #[MapInputName('created_at')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.u\Z')]
        public Carbon $createdAt,
        #[MapInputName('updated_at')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.u\Z')]
        public Carbon $updatedAt,
        #[MapInputName('meta')]
        public ?array $metadata = null,
    ) {
    }
}
