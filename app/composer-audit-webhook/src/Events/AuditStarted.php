<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Events;

/**
 * Fired immediately before the composer audit command is executed.
 */
class AuditStarted
{
    /**
     * @param  string  $path  The project path being audited.
     */
    public function __construct(
        public readonly string $path,
    ) {}
}
