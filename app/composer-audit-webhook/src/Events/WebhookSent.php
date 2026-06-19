<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Events;

/**
 * Fired after the webhook POST request is delivered successfully.
 */
class WebhookSent
{
    /**
     * @param  array<string, mixed>  $payload  The JSON payload that was sent to the webhook.
     * @param  string  $webhookUrl  The URL the payload was sent to.
     */
    public function __construct(
        public readonly array $payload,
        public readonly string $webhookUrl,
    ) {}
}
