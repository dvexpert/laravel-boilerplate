<?php

namespace Boilerplate\Console\Commands;

use Illuminate\Console\Command;
use Boilerplate\Console\Commands\Traits\SetupFiles;
use Boilerplate\Console\Commands\Traits\SetupPackagesAndConfig;

use function Laravel\Prompts\info;
use function Illuminate\Support\php_binary;

class BoilerplateInstallCommand extends Command
{
    use SetupFiles, SetupPackagesAndConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boilerplate:install-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Boilerplate Install Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Installing boilerplate...');

        $this->setupPackagesAndConfig();
        $this->setupFiles();
    }

    protected function phpBinary(): string
    {
        return php_binary();
    }
}
