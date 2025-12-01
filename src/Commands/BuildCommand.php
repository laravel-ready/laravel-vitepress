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
                            {--install : Install npm dependencies before building}';

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

        $this->info('Building VitePress documentation...');
        $this->newLine();

        try {
            // Check if node_modules exists or --install flag is set
            if ($this->option('install') || ! File::exists($sourcePath . '/node_modules')) {
                $this->components->task('Installing npm dependencies', function () use ($sourcePath) {
                    $this->runProcess(['npm', 'install'], $sourcePath);

                    return true;
                });
            }

            // Build VitePress
            $this->components->task('Building VitePress', function () use ($sourcePath) {
                $this->runProcess(['npm', 'run', 'docs:build'], $sourcePath);

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

        return storage_path('app/'.$path);
    }
}
