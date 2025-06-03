<?php

namespace Boilerplate\Console\Commands;

use Illuminate\Console\Command;
use Boilerplate\Console\Commands\Traits\{
    SetupAppProviders,
    SetupFiles,
    SetupPackagesAndConfig
};

use function Laravel\Prompts\info;

class BoilerplateInstallCommand extends Command
{
    use SetupAppProviders, SetupFiles, SetupPackagesAndConfig;

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
     * List packages to exclude from moving to composer.json.
     *
     * @var array
     */
    protected $boilerPlatePackages = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Installing boilerplate...');

        $this->boilerPlatePackages = [
            'nikic/php-parser',
        ];

        $this->setupPackagesAndConfig();
        $this->configureAppServiceProvider();
        $this->setupFiles();
    }

    protected function pintBinary(): string
    {
        return 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'pint';
    }
}
