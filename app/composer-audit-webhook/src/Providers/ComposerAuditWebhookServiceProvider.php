<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Providers;

use Illuminate\Support\ServiceProvider;
use YhyaSyrian\ComposerAuditWebhook\Commands\ComposerAuditWebhookCommand;
use YhyaSyrian\ComposerAuditWebhook\Services\AuditMessageFormatter;
use YhyaSyrian\ComposerAuditWebhook\Services\ComposerAuditRunner;
use YhyaSyrian\ComposerAuditWebhook\Services\WebhookNotifier;

class ComposerAuditWebhookServiceProvider extends ServiceProvider
{
    /**
     * Register package services into the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/composer-audit-webhook.php',
            'composer-audit-webhook'
        );

        $this->app->singleton(ComposerAuditRunner::class);
        $this->app->singleton(AuditMessageFormatter::class);
        $this->app->singleton(WebhookNotifier::class);
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/composer-audit-webhook.php' =>
                config_path('composer-audit-webhook.php'),
        ], 'composer-audit-webhook-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ComposerAuditWebhookCommand::class,
            ]);
        }
    }

    /**
     * The services provided by this provider (for deferred loading awareness).
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            ComposerAuditRunner::class,
            AuditMessageFormatter::class,
            WebhookNotifier::class,
        ];
    }
}
