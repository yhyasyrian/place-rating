<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Events;

/**
 * Fired when the webhook POST request fails to deliver.
 */
class WebhookFailed
{
    /**
     * @param  array<string, mixed>  $payload  The JSON payload that failed to be delivered.
     * @param  string  $webhookUrl  The URL the payload was being sent to.
     * @param  string  $error  A human-readable description of the failure reason.
     */
    public function __construct(
        public readonly array $payload,
        public readonly string $webhookUrl,
        public readonly string $error,
    ) {}
}
