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

        $this->copyAppDirectory();
        $this->copyBootstrap();
        $this->copyAppConfigs();
        $this->copyDatabaseStructure();
        $this->copyResources();
        $this->copyRoutes();
        $this->copyScripts();
        $this->copyTests();
    }

    private function copyAppDirectory()
    {
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../app/Console', base_path('app/Console'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../app/Enums', base_path('app/Enums'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../app/Helper', base_path('app/Helper'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../app/Http', base_path('app/Http'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../app/Models', base_path('app/Models'));
        (new Filesystem)->copy(__DIR__ . '/../../../../app/Providers/TelescopeServiceProvider.php', base_path('app/Providers/TelescopeServiceProvider.php'));
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

    private function copyDatabaseStructure()
    {
        (new Filesystem)->copy(__DIR__ . '/../../../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../database/migrations', base_path('database/migrations'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../database/seeders', base_path('database/seeders'));
    }

    private function copyResources()
    {
        (new Filesystem)->cleanDirectory(base_path('resources'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../resources', base_path('resources'));
    }

    private function copyRoutes()
    {
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../routes', base_path('routes'));
    }

    private function copyScripts()
    {
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../scripts', base_path('scripts'));
    }

    private function copyTests()
    {
        (new Filesystem)->copyDirectory(__DIR__ . '/../../../../tests', base_path('tests'));
    }
}
