<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Events;

/**
 * Fired when one or more vulnerabilities are found in the audit result.
 */
class VulnerabilitiesDetected
{
    /**
     * @param  array<string, mixed>  $auditResult  The parsed composer audit JSON output.
     * @param  string  $message  The formatted human-readable security alert message.
     */
    public function __construct(
        public readonly array $auditResult,
        public readonly string $message,
    ) {}
}
