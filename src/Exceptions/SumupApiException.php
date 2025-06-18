<?php

namespace Sumup\Laravel\Exceptions;

use Exception;

class SumupApiException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?array $errors = null,
        public readonly ?int $statusCode = null,
        public readonly ?string $requestId = null
    ) {
        parent::__construct($message);
    }

    public static function fromResponse(array $response, int $statusCode): self
    {
        $message = $response['message'] ?? 'En ukendt fejl opstod ved kommunikation med Sumup API';
        $errors = $response['errors'] ?? null;
        $requestId = $response['request_id'] ?? null;

        return new self($message, $errors, $statusCode, $requestId);
    }
} 