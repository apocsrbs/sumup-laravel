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
        $errors = $response['errors'] ?? null;
        $requestId = $response['request_id'] ?? $response['trace_id'] ?? null;

        // SumUp bruger tre fejlformater: {"message":...}, {"errors":{"detail":...,"type":...}}
        // (reader-endpoints) og RFC 7807 {"title":...,"detail":...} (nyere endpoints).
        $message = $response['message']
            ?? (is_array($errors) ? ($errors['detail'] ?? $errors['message'] ?? null) : null)
            ?? $response['detail']
            ?? $response['title']
            ?? 'En ukendt fejl opstod ved kommunikation med Sumup API';

        return new self($message, $errors, $statusCode, $requestId);
    }

    /**
     * Fejltypen fra reader-endpoints, fx READER_BUSY eller READER_OFFLINE.
     */
    public function errorType(): ?string
    {
        $type = $this->errors['type'] ?? null;

        return is_string($type) && $type !== '' ? $type : null;
    }
} 