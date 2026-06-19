<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use YhyaSyrian\ComposerAuditWebhook\Events\WebhookFailed;
use YhyaSyrian\ComposerAuditWebhook\Events\WebhookSent;

class WebhookNotifier
{
    /**
     * Send a POST request to the configured webhook URL with the given payload.
     *
     * Applies HTTP Basic Auth when both a username and password are configured.
     * Dispatches `WebhookSent` on success and `WebhookFailed` on any error.
     *
     * @param  string  $webhookUrl  The target webhook endpoint.
     * @param  array<string, mixed>  $payload  The JSON body to send.
     * @param  string|null  $username  Optional Basic Auth username.
     * @param  string|null  $password  Optional Basic Auth password.
     *
     * @throws \RuntimeException When the HTTP request fails or returns a non-2xx status.
     */
    public function send(
        string $webhookUrl,
        array $payload,
        ?string $username = null,
        ?string $password = null,
    ): void {
        try {
            $client = Http::acceptJson();

            if ($username !== null && $username !== '' && $password !== null && $password !== '') {
                $client = $client->withBasicAuth($username, $password);
            }

            $response = $client->post($webhookUrl, $payload);

            $response->throw();

            event(new WebhookSent($payload, $webhookUrl));
        } catch (RequestException $e) {
            $error = sprintf(
                'HTTP %d — %s',
                $e->response->status(),
                $e->response->body()
            );

            event(new WebhookFailed($payload, $webhookUrl, $error));

            throw new \RuntimeException("Webhook request failed: {$error}", 0, $e);
        } catch (\Throwable $e) {
            $error = $e->getMessage();

            event(new WebhookFailed($payload, $webhookUrl, $error));

            throw new \RuntimeException("Webhook request failed: {$error}", 0, $e);
        }
    }
}
