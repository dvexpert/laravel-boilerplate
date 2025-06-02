<?php

namespace Boilerplate\Console\Commands\Traits;

use Illuminate\Filesystem\Filesystem;
use Boilerplate\Console\Commands\BoilerplateInstallCommand;

use function Laravel\Prompts\warning;

trait SetupFiles
{
    private function setupFiles()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Copying resources...');

        $this->copyResources();
        $this->copyBootstrap();
        $this->copyAppConfigs();
    }

    private function copyResources()
    {
        (new Filesystem)->cleanDirectory(base_path('resources'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../resources', base_path('resources'));
    }

    private function copyBootstrap()
    {
        (new Filesystem)->copy(__DIR__ . '/../../../../bootstrap/app.php', base_path('bootstrap/app.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../bootstrap/providers.php', base_path('bootstrap/providers.php'));
    }

    private function copyAppConfigs()
    {
        (new Filesystem)->copy(__DIR__ . '/../../../../config/audit.php', base_path('config/audit.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../config/log-viewer.php', base_path('config/log-viewer.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../config/permission.php', base_path('config/permission.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../config/pulse.php', base_path('config/pulse.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../config/telescope.php', base_path('config/telescope.php'));
    }
}
