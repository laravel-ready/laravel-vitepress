<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vitepress:build
                            {--source= : Source directory path}
                            {--output= : Output directory path}
                            {--install : Install dependencies before building}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build VitePress documentation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sourcePath = $this->option('source') ?? config('vitepress.build.source_path');
        $outputPath = $this->option('output') ?? $this->getDefaultOutputPath();

        if (! File::exists($sourcePath)) {
            $this->components->error("Source directory not found: {$sourcePath}");
            $this->newLine();
            $this->components->info('Run <comment>php artisan vitepress:publish --stubs</comment> to create VitePress source files.');

            return self::FAILURE;
        }

        $packageManager = $this->detectPackageManager($sourcePath);
        $this->info("Building VitePress documentation using {$packageManager}...");
        $this->newLine();

        try {
            // Always install if node_modules doesn't exist or --install flag is set
            $needsInstall = $this->option('install') || ! File::exists($sourcePath . '/node_modules');

            if ($needsInstall) {
                $this->components->task("Installing dependencies ({$packageManager})", function () use ($sourcePath, $packageManager) {
                    $this->runProcess([$packageManager, 'install'], $sourcePath);

                    return true;
                });

                // Verify node_modules was created
                if (! File::exists($sourcePath . '/node_modules')) {
                    $this->components->error('Failed to install dependencies. node_modules directory not found.');
                    $this->newLine();
                    $this->line("Try running manually: cd {$sourcePath} && {$packageManager} install");

                    return self::FAILURE;
                }
            }

            // Build VitePress
            $this->components->task('Building VitePress', function () use ($sourcePath, $packageManager) {
                $this->runProcess([$packageManager, 'run', 'docs:build'], $sourcePath);

                return true;
            });

            // Copy built files to output directory
            $distPath = $sourcePath . '/.vitepress/dist';

            if (! File::exists($distPath)) {
                $this->components->error('Build output not found. Build may have failed.');

                return self::FAILURE;
            }

            $this->components->task('Copying built files to output directory', function () use ($distPath, $outputPath) {
                File::ensureDirectoryExists($outputPath);
                File::copyDirectory($distPath, $outputPath);

                return true;
            });

            $this->newLine();
            $this->components->info('Documentation built successfully!');
            $this->newLine();
            $this->line("  <comment>Output:</comment> {$outputPath}");
            $this->newLine();

            return self::SUCCESS;
        } catch (ProcessFailedException $e) {
            $this->newLine();
            $this->components->error('Build failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Detect the package manager used in the project.
     */
    protected function detectPackageManager(string $docsPath): string
    {
        // Check Laravel project root first (user's preferred package manager)
        $basePath = base_path();

        if (File::exists("{$basePath}/pnpm-lock.yaml")) {
            return 'pnpm';
        }

        if (File::exists("{$basePath}/yarn.lock")) {
            return 'yarn';
        }

        if (File::exists("{$basePath}/bun.lockb") || File::exists("{$basePath}/bun.lock")) {
            return 'bun';
        }

        if (File::exists("{$basePath}/package-lock.json")) {
            return 'npm';
        }

        // Fall back to checking docs directory
        if (File::exists("{$docsPath}/pnpm-lock.yaml")) {
            return 'pnpm';
        }

        if (File::exists("{$docsPath}/yarn.lock")) {
            return 'yarn';
        }

        if (File::exists("{$docsPath}/bun.lockb") || File::exists("{$docsPath}/bun.lock")) {
            return 'bun';
        }

        if (File::exists("{$docsPath}/package-lock.json")) {
            return 'npm';
        }

        // Default to npm
        return 'npm';
    }

    /**
     * Run a process and stream output.
     *
     * @param  array<string>  $command
     */
    protected function runProcess(array $command, string $cwd): void
    {
        $process = new Process($command, $cwd);
        $process->setTimeout(300); // 5 minutes

        $process->run(function ($type, $buffer) {
            if ($this->output->isVerbose()) {
                $this->output->write($buffer);
            }
        });

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Get the default output path based on configuration.
     */
    protected function getDefaultOutputPath(): string
    {
        $storage = config('vitepress.assets.storage', 'storage');
        $path = config('vitepress.assets.path', 'vitepress/docs');

        if ($storage === 'public') {
            return public_path($path);
        }

        return storage_path('app/' . $path);
    }
}
