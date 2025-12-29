<?php
declare(strict_types=1);

namespace SimpleVC\Error;

use Psr\Log\AbstractLogger;
use Stringable;
use Throwable;
use function SimpleVC\env;

/**
 * Logger implementation that writes to `STDERR` (console output).
 *
 * This logger writes log messages to `STDERR` when the debug mode is enabled. It's primarily used during development
 *  to display errors in the terminal where the PHP development server was started.
 *
 * Only logs when the `DEBUG` environment variable is set to `true`.
 */
class ConsoleLogger extends AbstractLogger
{
    /**
     * Interpolates context values into the message placeholders.
     *
     * @param \Stringable|string $message
     * @param array<string, mixed> $context
     * @return string
     */
    private function interpolate(string|Stringable $message, array $context): string
    {
        $message = (string)$message;
        $replace = [];

        foreach ($context as $key => $val) {
            // Check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param \Stringable|string $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        if (!env('DEBUG', false)) {
            return;
        }

        $stderr = fopen('php://stderr', 'w');
        if ($stderr === false) {
            return;
        }

        // Interpolate context values into message placeholders
        $interpolatedMessage = $this->interpolate($message, $context);

        // Write the interpolated message
        fwrite($stderr, $interpolatedMessage . "\n");

        // If there's an exception in context, write its stack trace
        $exception = $context['exception'] ?? null;
        if ($exception instanceof Throwable) {
            fwrite($stderr, $exception->getTraceAsString() . "\n\n");
        }

        fclose($stderr);
    }
}
