<?php

declare(strict_types=1);

namespace YhyaSyrian\ComposerAuditWebhook\Services;

use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Support\Facades\Process;

class ComposerAuditRunner
{
    /**
     * Run `composer audit --format=json` in the given working directory
     * and return the decoded result as an associative array.
     *
     * Composer audit exits with a non-zero code when vulnerabilities are found,
     * so we must not throw on failure — only on a truly broken invocation.
     *
     * @param  string  $workingDirectory  Absolute path to the project root.
     * @return array<string, mixed>
     *
     * @throws \RuntimeException When the command cannot be executed or outputs invalid JSON.
     */
    public function run(string $workingDirectory): array
    {
        $result = Process::path($workingDirectory)
            ->run('composer audit --format=json');

        $output = trim($result->output() ?: $result->errorOutput());

        if (empty($output)) {
            throw new \RuntimeException(
                'composer audit produced no output. '
                . 'Exit code: ' . $result->exitCode()
            );
        }

        $decoded = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Failed to parse composer audit JSON output: ' . json_last_error_msg()
                . "\nRaw output: " . $output
            );
        }

        return $decoded;
    }
}
