<?php
declare(strict_types=1);

namespace SimpleVC\Exception;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a required template file is not found.
 */
class TemplateFileNotFound extends RuntimeException
{
    /**
     * @inheritDoc
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
