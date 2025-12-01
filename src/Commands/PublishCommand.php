<?php

declare(strict_types=1);

namespace LaravelReady\VitePress\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vitepress:publish
                            {--config : Publish configuration only}
                            {--assets : Publish assets only}
                            {--stubs : Publish stubs only}
                            {--views : Publish views only}
                            {--all : Publish all resources}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Laravel VitePress resources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tags = $this->determineTags();

        if (empty($tags)) {
            $this->components->error('Please specify what to publish using --config, --assets, --stubs, --views, or --all');

            return self::FAILURE;
        }

        $this->info('Publishing Laravel VitePress resources...');
        $this->newLine();

        foreach ($tags as $tag) {
            $this->components->task("Publishing {$tag}", function () use ($tag) {
                $this->callSilently('vendor:publish', [
                    '--tag' => "vitepress-{$tag}",
                    '--force' => $this->option('force'),
                ]);

                return true;
            });
        }

        $this->newLine();
        $this->components->info('Resources published successfully!');

        return self::SUCCESS;
    }

    /**
     * Determine which tags to publish.
     *
     * @return array<string>
     */
    protected function determineTags(): array
    {
        if ($this->option('all')) {
            return ['config', 'assets', 'stubs', 'views'];
        }

        $tags = [];

        if ($this->option('config')) {
            $tags[] = 'config';
        }

        if ($this->option('assets')) {
            $tags[] = 'assets';
        }

        if ($this->option('stubs')) {
            $tags[] = 'stubs';
        }

        if ($this->option('views')) {
            $tags[] = 'views';
        }

        return $tags;
    }
}
