# yhyasyrian/composer-audit-webhook

A Laravel package that runs `composer audit`, detects vulnerable dependencies, and POSTs a formatted security alert to a configurable webhook URL.

---

## Features

- Runs `composer audit --format=json` via Laravel's Process component
- Detects vulnerabilities and formats a clear, human-readable alert message
- Sends a POST request (with optional HTTP Basic Auth) to any webhook endpoint
- Fires lifecycle events: `AuditStarted`, `AuditCompleted`, `VulnerabilitiesDetected`, `WebhookSent`, `WebhookFailed`
- Supports Laravel Scheduler for automated, recurring audits
- Compatible with **Laravel 11, 12, and 13**
- Auto-discoverable via Laravel package discovery

---

## Requirements

- PHP 8.2+
- Laravel 11, 12, or 13
- Composer available in `$PATH` on the server

---

## Installation

Because this is a private package, you must point Composer at the VCS repository before requiring it.

### 1. Add the repository to your application's `composer.json`

```json
{
    "autoload": {
        "psr-4": {
            "YhyaSyrian\\ComposerAuditWebhook\\": "app/composer-audit-webhook/src/"
        }
    }
}
```

### 2. Require the package

```bash
composer require yhyasyrian/composer-audit-webhook
```

Composer will fetch the package directly from the configured VCS repository.

---

## Auto-Discovery

The package registers itself automatically via Laravel's package discovery mechanism.  
No manual provider registration is required.

If you have disabled auto-discovery, add the provider manually to `bootstrap/providers.php` (Laravel 11+):

```php
return [
    // ...
    YhyaSyrian\ComposerAuditWebhook\Providers\ComposerAuditWebhookServiceProvider::class,
];
```

---

## Configuration

### Publish the config file

```bash
php artisan vendor:publish --tag=composer-audit-webhook-config
```

This creates `config/composer-audit-webhook.php` in your application.

### Environment variables

Add the following to your `.env` file:

```env
COMPOSER_AUDIT_WEBHOOK_URL=https://your-webhook-endpoint.example.com/hook
COMPOSER_AUDIT_WEBHOOK_USERNAME=
COMPOSER_AUDIT_WEBHOOK_PASSWORD=
```

| Variable                          | Required | Description                                               |
|-----------------------------------|----------|-----------------------------------------------------------|
| `COMPOSER_AUDIT_WEBHOOK_URL`      | Yes      | The URL that receives the security alert POST request     |
| `COMPOSER_AUDIT_WEBHOOK_USERNAME` | No       | HTTP Basic Auth username (leave blank to skip auth)       |
| `COMPOSER_AUDIT_WEBHOOK_PASSWORD` | No       | HTTP Basic Auth password (leave blank to skip auth)       |

`APP_NAME` (already in your `.env`) is used as the `site` label in the alert message.

---

## Usage

Run the audit manually at any time:

```bash
php artisan composer-audit:webhook
```

### Exit codes

| Code | Meaning                                                |
|------|--------------------------------------------------------|
| `0`  | No vulnerabilities found, or alert delivered successfully |
| `1`  | Vulnerabilities found but webhook delivery failed      |
| `2`  | `composer audit` failed or returned unparseable output |

---

## Scheduler Integration

Add one of the following to your `routes/console.php` (Laravel 11+) or `app/Console/Kernel.php`:

### Hourly (recommended default)

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('composer-audit:webhook')->hourly();
```

### Daily

```php
Schedule::command('composer-audit:webhook')->daily();
```

### Twice daily

```php
Schedule::command('composer-audit:webhook')->twiceDaily(8, 20);
```

Make sure your server's cron is configured to run the Laravel scheduler:

```cron
* * * * * cd /var/www/your-app && php artisan schedule:run >> /dev/null 2>&1
```

---

## Example Webhook Payload

When vulnerabilities are detected the package sends this JSON body:

```json
{
    "site": "My Laravel App",
    "path": "/var/www/example.com",
    "message": "Detected vulnerable packages:\n\n1. Package: laravel/framework\n   Severity: High\n   CVE: CVE-2024-XXXX\n   Title: Example vulnerability\n   Link: https://github.com/advisories/GHSA-xxxx\n   Affected Versions: <11.0.0\n\nRecommended Action:\nRun composer update for the affected packages after reviewing compatibility."
}
```

---

## Events

The package dispatches events at every meaningful stage of the lifecycle.  
Register listeners in your `AppServiceProvider` or a dedicated event service provider.

### Available Events

| Event                                                        | When it fires                                          |
|--------------------------------------------------------------|--------------------------------------------------------|
| `YhyaSyrian\ComposerAuditWebhook\Events\AuditStarted`             | Just before `composer audit` runs                     |
| `YhyaSyrian\ComposerAuditWebhook\Events\AuditCompleted`           | After audit output is parsed successfully              |
| `YhyaSyrian\ComposerAuditWebhook\Events\VulnerabilitiesDetected`  | When at least one vulnerability is found               |
| `YhyaSyrian\ComposerAuditWebhook\Events\WebhookSent`              | After a successful webhook delivery                    |
| `YhyaSyrian\ComposerAuditWebhook\Events\WebhookFailed`            | When the webhook request fails                         |

### Listener example

```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\Event;
use YhyaSyrian\ComposerAuditWebhook\Events\VulnerabilitiesDetected;
use YhyaSyrian\ComposerAuditWebhook\Events\WebhookFailed;

public function boot(): void
{
    // Log a Slack notification when vulnerabilities are found
    Event::listen(VulnerabilitiesDetected::class, function (VulnerabilitiesDetected $event) {
        // $event->auditResult — raw parsed JSON from composer audit
        // $event->message     — formatted alert text
        \Log::critical('Composer audit found vulnerabilities', [
            'packages' => array_keys($event->auditResult['advisories'] ?? []),
        ]);
    });

    // Alert your ops team when the webhook delivery fails
    Event::listen(WebhookFailed::class, function (WebhookFailed $event) {
        // $event->payload    — the JSON body that could not be delivered
        // $event->webhookUrl — the target URL
        // $event->error      — the error description
        \Log::error('Composer audit webhook failed', [
            'url'   => $event->webhookUrl,
            'error' => $event->error,
        ]);
    });
}
```

### Full event payload reference

```php
// AuditStarted
$event->path;                // string — project root path

// AuditCompleted
$event->auditResult;         // array  — parsed composer audit output
$event->hasVulnerabilities;  // bool

// VulnerabilitiesDetected
$event->auditResult;         // array  — parsed composer audit output
$event->message;             // string — formatted alert message

// WebhookSent
$event->payload;             // array  — JSON body that was delivered
$event->webhookUrl;          // string — target URL

// WebhookFailed
$event->payload;             // array  — JSON body that failed to deliver
$event->webhookUrl;          // string — target URL
$event->error;               // string — human-readable error description
```

---

## Troubleshooting

### "composer audit produced no output"

Ensure `composer` is available in `$PATH` for the user that runs the Laravel scheduler/queue worker.  
Test with:

```bash
sudo -u www-data composer audit --format=json
```

### "Failed to parse composer audit JSON output"

Run `composer audit --format=json` manually in your project root and verify the output is valid JSON.  
Some Composer versions print warnings to stdout before the JSON — update to Composer 2.4+ to resolve this.

### "No webhook URL configured"

Set `COMPOSER_AUDIT_WEBHOOK_URL` in your `.env` file and run `php artisan config:clear`.

### Webhook returns a non-2xx status

The `WebhookFailed` event is dispatched with the HTTP status and response body in `$event->error`.  
Enable debug logging on your webhook receiver to inspect the incoming payload.

### The command never runs on the scheduler

Confirm the cron entry is active:

```bash
crontab -l
```

And that `schedule:run` produces output:

```bash
php artisan schedule:run --verbose
```

---

## License

MIT
