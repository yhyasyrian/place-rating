<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Services;

class AuditMessageFormatter
{
    /**
     * Determine whether the given audit result contains any advisories.
     *
     * @param  array<string, mixed>  $auditResult
     */
    public function hasVulnerabilities(array $auditResult): bool
    {
        $advisories = $auditResult['advisories'] ?? [];

        return ! empty($advisories);
    }

    /**
     * Build a human-readable security alert message from the audit result.
     *
     * @param  array<string, mixed>  $auditResult
     * @param  string  $site  Application name to include in the header.
     * @param  string  $path  Project path to include in the header.
     */
    public function format(array $auditResult, string $site, string $path): string
    {
        $advisories = $auditResult['advisories'] ?? [];

        $lines = [];
        $lines[] = 'Detected vulnerable packages:';
        $lines[] = '';

        $index = 1;

        foreach ($advisories as $packageName => $packageAdvisories) {
            foreach ($packageAdvisories as $advisory) {
                $cve       = $this->extractCve($advisory);
                $severity  = ucfirst($advisory['severity'] ?? 'unknown');
                $title     = $advisory['title'] ?? 'No title available';
                $link      = $advisory['link'] ?? $advisory['url'] ?? 'N/A';
                $affected  = $this->formatAffectedVersions($advisory);

                $lines[] = "{$index}. Package: {$packageName}";
                $lines[] = "   Severity: {$severity}";
                $lines[] = "   CVE: {$cve}";
                $lines[] = "   Title: {$title}";
                $lines[] = "   Link: {$link}";
                $lines[] = "   Affected Versions: {$affected}";
                $lines[] = '';

                $index++;
            }
        }

        $lines[] = 'Recommended Action:';
        $lines[] = 'Run composer update for the affected packages after reviewing compatibility.';

        return implode("\n", $lines);
    }

    /**
     * Extract the first CVE identifier from the advisory's CVE list, or return "N/A".
     *
     * @param  array<string, mixed>  $advisory
     */
    private function extractCve(array $advisory): string
    {
        $cves = $advisory['cve'] ?? $advisory['cves'] ?? [];

        if (is_string($cves) && $cves !== '') {
            return $cves;
        }

        if (is_array($cves) && ! empty($cves)) {
            return implode(', ', $cves);
        }

        return 'N/A';
    }

    /**
     * Build a readable affected-versions string from the advisory's reported ranges.
     *
     * @param  array<string, mixed>  $advisory
     */
    private function formatAffectedVersions(array $advisory): string
    {
        // composer audit may use different keys depending on the version
        $affectedVersions = $advisory['affectedVersions']
            ?? $advisory['affected_versions']
            ?? $advisory['versions']
            ?? null;

        if (is_string($affectedVersions) && $affectedVersions !== '') {
            return $affectedVersions;
        }

        if (is_array($affectedVersions) && ! empty($affectedVersions)) {
            return implode(', ', $affectedVersions);
        }

        $reportedAt = $advisory['reportedAt'] ?? $advisory['reported_at'] ?? null;

        return $reportedAt ? "Reported: {$reportedAt}" : 'N/A';
    }
}
