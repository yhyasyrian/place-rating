<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Commands;

use Illuminate\Console\Command;
use YhyaSyrian\ComposerAuditWebhook\Events\AuditCompleted;
use YhyaSyrian\ComposerAuditWebhook\Events\AuditStarted;
use YhyaSyrian\ComposerAuditWebhook\Events\VulnerabilitiesDetected;
use YhyaSyrian\ComposerAuditWebhook\Services\AuditMessageFormatter;
use YhyaSyrian\ComposerAuditWebhook\Services\ComposerAuditRunner;
use YhyaSyrian\ComposerAuditWebhook\Services\WebhookNotifier;

class ComposerAuditWebhookCommand extends Command
{
    /**
     * Exit code: everything is fine, no vulnerabilities found or webhook sent successfully.
     */
    private const EXIT_OK = 0;

    /**
     * Exit code: vulnerabilities were found but the webhook could not be delivered.
     */
    private const EXIT_WEBHOOK_FAILED = 1;

    /**
     * Exit code: composer audit command failed or its output could not be parsed.
     */
    private const EXIT_AUDIT_FAILED = 2;

    protected $signature = 'composer-audit:webhook';

    protected $description = 'Run composer audit and send a security alert to the configured webhook if vulnerabilities are found.';

    public function __construct(
        private readonly ComposerAuditRunner $runner,
        private readonly AuditMessageFormatter $formatter,
        private readonly WebhookNotifier $notifier,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $path    = (string) config('composer-audit-webhook.path', base_path());
        $site    = (string) config('composer-audit-webhook.site', 'Laravel Application');
        $webhook = config('composer-audit-webhook.webhook');
        $username = config('composer-audit-webhook.basic_auth.username');
        $password = config('composer-audit-webhook.basic_auth.password');

        // ── Step 1: Run composer audit ────────────────────────────────────────
        $this->info('Running composer audit...');
        event(new AuditStarted($path));

        try {
            $auditResult = $this->runner->run($path);
        } catch (\RuntimeException $e) {
            $this->error('composer audit failed: ' . $e->getMessage());

            return self::EXIT_AUDIT_FAILED;
        }

        // ── Step 2: Parse result ──────────────────────────────────────────────
        $hasVulnerabilities = $this->formatter->hasVulnerabilities($auditResult);

        event(new AuditCompleted($auditResult, $hasVulnerabilities));

        if (! $hasVulnerabilities) {
            $this->info('✅ No vulnerabilities found. Nothing to report.');

            return self::EXIT_OK;
        }

        // ── Step 3: Format message ────────────────────────────────────────────
        $message = $this->formatter->format($auditResult, $site, $path);

        event(new VulnerabilitiesDetected($auditResult, $message));

        $advisoryCount = $this->countAdvisories($auditResult);
        $this->warn("⚠️  {$advisoryCount} vulnerability/vulnerabilities detected.");
        $this->line($message);

        // ── Step 4: Validate webhook URL ──────────────────────────────────────
        if (empty($webhook)) {
            $this->error('No webhook URL configured. Set COMPOSER_AUDIT_WEBHOOK_URL in your .env file.');

            return self::EXIT_WEBHOOK_FAILED;
        }

        // ── Step 5: Send webhook ──────────────────────────────────────────────
        $payload = [
            'site'    => $site,
            'path'    => $path,
            'message' => $message,
        ];

        $this->info('Sending security alert to webhook...');

        try {
            $this->notifier->send(
                webhookUrl: (string) $webhook,
                payload: $payload,
                username: $username ? (string) $username : null,
                password: $password ? (string) $password : null,
            );
        } catch (\RuntimeException $e) {
            $this->error('Failed to deliver webhook: ' . $e->getMessage());

            return self::EXIT_WEBHOOK_FAILED;
        }

        $this->info('✅ Security alert sent successfully.');

        return self::EXIT_OK;
    }

    /**
     * Count the total number of individual advisories across all packages.
     *
     * @param  array<string, mixed>  $auditResult
     */
    private function countAdvisories(array $auditResult): int
    {
        $count = 0;

        foreach ($auditResult['advisories'] ?? [] as $advisories) {
            $count += count($advisories);
        }

        return $count;
    }
}
