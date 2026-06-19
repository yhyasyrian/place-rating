<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Events;

/**
 * Fired after composer audit completes and the output has been parsed successfully.
 */
class AuditCompleted
{
    /**
     * @param  array<string, mixed>  $auditResult  The parsed composer audit JSON output.
     * @param  bool  $hasVulnerabilities  Whether any advisories were detected.
     */
    public function __construct(
        public readonly array $auditResult,
        public readonly bool $hasVulnerabilities,
    ) {}
}
