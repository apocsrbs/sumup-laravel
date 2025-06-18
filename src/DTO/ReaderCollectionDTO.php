<?php

namespace Sumup\Laravel\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class ReaderCollectionDTO extends Data
{
    /**
     * @param array<ReaderDTO> $items
     */
    public function __construct(
        #[DataCollectionOf(ReaderDTO::class)]
        public array $items = [],
    ) {
    }
} 