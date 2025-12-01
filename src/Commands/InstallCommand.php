<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vitepress:install
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel VitePress package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Laravel VitePress...');
        $this->newLine();

        // Publish configuration
        $this->components->task('Publishing configuration', function () {
            $this->callSilently('vendor:publish', [
                '--tag' => 'vitepress-config',
                '--force' => $this->option('force'),
            ]);

            return true;
        });

        // Publish assets
        $this->components->task('Publishing documentation assets', function () {
            $this->callSilently('vendor:publish', [
                '--tag' => 'vitepress-assets',
                '--force' => $this->option('force'),
            ]);

            return true;
        });

        $this->newLine();

        // Publish stubs (optional)
        if ($this->confirm('Do you want to publish VitePress source stubs for customization?', false)) {
            $this->components->task('Publishing VitePress stubs', function () {
                $this->callSilently('vendor:publish', [
                    '--tag' => 'vitepress-stubs',
                    '--force' => $this->option('force'),
                ]);

                return true;
            });
        }

        $this->newLine();
        $this->components->info('Laravel VitePress installed successfully!');
        $this->newLine();

        $this->components->bulletList([
            'Configure your documentation in <comment>config/vitepress.php</comment>',
            'Visit <comment>'.url(config('vitepress.route.prefix', 'docs')).'</comment> to view documentation',
            'Run <comment>php artisan vitepress:publish --stubs</comment> to customize VitePress source',
        ]);

        $this->newLine();

        return self::SUCCESS;
    }
}
