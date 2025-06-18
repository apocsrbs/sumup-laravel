<?php

namespace Sumup\Laravel\DTO;

use Spatie\LaravelData\Data;

class DeviceDTO extends Data
{
    public function __construct(
        public string $identifier,
        public string $model,
    ) {
    }
}
