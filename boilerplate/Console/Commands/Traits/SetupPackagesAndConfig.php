<?php

namespace Boilerplate\Console\Commands\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Boilerplate\Console\Commands\BoilerplateInstallCommand;

use function Laravel\Prompts\warning;

/**
 * @template T of array{require: array<string, string>, require-dev: array<string, string>}
 */
trait SetupPackagesAndConfig
{
    protected function setupPackagesAndConfig()
    {
        /** @var BoilerplateInstallCommand $this */
        $this->vscodeSettings();
        $this->setupNpmPackages();
        $this->setupComposerPackages();
        $this->copyConfigs();
    }

    private function vscodeSettings()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Writing vscode settings...');
        // Check if vscode workspace setting config file exists or not in the at `.vscode/settings.json`
        if (! (new Filesystem)->exists(base_path('.vscode'))) {
            (new Filesystem)->makeDirectory(base_path('.vscode'));
        }

        if (! (new Filesystem)->exists(base_path('.vscode/settings.json'))) {
            (new Filesystem)->put(base_path('.vscode/settings.json'), '{}');
        }

        $content           = json_decode(file_get_contents(base_path('.vscode/settings.json')), true);
        $boilerplateConfig = json_decode(file_get_contents(__DIR__ . '/../../../../.vscode/settings.json'), true);
        $finalContent      = array_merge($boilerplateConfig, $content);

        file_put_contents(base_path('.vscode/settings.json'), json_encode($finalContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
    }

    private function setupNpmPackages()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Installing npm packages...');

        $boilerplateContent = json_decode(file_get_contents(__DIR__ . '/../../../../package.json'), true);
        $content            = json_decode(file_get_contents(base_path('package.json')), true);

        $content['scripts']              = array_unique(array_merge(data_get($boilerplateContent, 'scripts', []), data_get($content, 'scripts', [])));
        $content['devDependencies']      = $this->mergeDependenciesWithPriority(data_get($boilerplateContent, 'devDependencies', []), data_get($content, 'devDependencies', []));
        $content['dependencies']         = $this->mergeDependenciesWithPriority(data_get($boilerplateContent, 'dependencies', []), data_get($content, 'dependencies', []));
        $content['optionalDependencies'] = $this->mergeDependenciesWithPriority(data_get($boilerplateContent, 'optionalDependencies', []), data_get($content, 'optionalDependencies', []));
        $content['packageManager']       = $boilerplateContent['packageManager'];

        file_put_contents(base_path('package.json'), json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

        Process::timeout(0)
            ->path(base_path())
            ->run(
                ['yarn', 'install'],
                function (string $type, string $output) {
                    $this->output->write($output);
                }
            );
    }

    private function setupComposerPackages()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Updating composer...');

        $boilerplateComposerContent = json_decode(file_get_contents(__DIR__ . '/../../../../composer.json'), true);
        $composerContent            = json_decode(file_get_contents(base_path('composer.json')), true);

        $composerContent['require']                           = $this->mergeDependenciesWithPriority($boilerplateComposerContent['require'], $composerContent['require']);
        $composerContent['require-dev']                       = $this->mergeDependenciesWithPriority($boilerplateComposerContent['require-dev'], $composerContent['require-dev']);
        $composerContent['scripts']['dev']                    = $boilerplateComposerContent['scripts']['dev'];
        $composerContent['extra']['laravel']['dont-discover'] = array_unique(array_merge($boilerplateComposerContent['extra']['laravel']['dont-discover'], $composerContent['extra']['laravel']['dont-discover']));

        file_put_contents(base_path('composer.json'), json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

        Process::timeout(0)
            ->path(base_path())
            ->run(
                ['composer', 'update'],
                function (string $type, string $output) {
                    $this->output->write($output);
                }
            );
    }

    /**
     * Merges two arrays of dependencies, giving priority to the versions in the
     * override array if they are higher than those in the base array. It strips
     * the version prefixes '^' and '~' for comparison.
     *
     * @param  array  $base  The base array of dependencies with package names as keys and versions as values.
     * @param  array  $override  The override array of dependencies with package names as keys and versions as values.
     * @return array The merged array of dependencies.
     */
    private function mergeDependenciesWithPriority(array $base, array $override): array
    {
        $merged = $base;

        foreach ($override as $package => $version) {
            if (! isset($merged[$package])) {
                $merged[$package] = $version;

                continue;
            }

            // Basic version compare ignoring ^ and ~
            $baseVersion     = ltrim($merged[$package], '^~');
            $overrideVersion = ltrim($version, '^~');

            if (version_compare($overrideVersion, $baseVersion, '>')) {
                $merged[$package] = $version;
            }
        }

        return $merged;
    }

    /**
     * Copy the config files to the root of the project.
     *
     * This copies the following files:
     * - _ide_helper.php
     * - _ide_helper_models.php
     * - .env.testing
     * - .gitignore (adds /coverage if not present)
     * - .prettierignore
     * - .prettierrc
     * - components.json
     * - eslint.config.js
     * - pint.json
     * - rector.php
     * - tsconfig.json
     * - vite.config.ts (deletes vite.config.js)
     */
    private function copyConfigs()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Copying config files...');

        // Ide helper
        (new Filesystem)->copy(__DIR__ . '/../../../../_ide_helper_models.php', base_path('_ide_helper_models.php'));
        (new Filesystem)->copy(__DIR__ . '/../../../../_ide_helper.php', base_path('_ide_helper.php'));

        // testing environment.
        (new Filesystem)->copy(__DIR__ . '/../../../../.env.testing', base_path('.env.testing'));

        // add /coverage to .gitignore file if not present in the file
        if (! str_contains((new Filesystem)->get(base_path('.gitignore')), '/coverage')) {
            (new Filesystem)->append(base_path('.gitignore'), PHP_EOL . '/coverage' . PHP_EOL);
        }

        // prettier config
        (new Filesystem)->copy(__DIR__ . '/../../../../.prettierignore', base_path('.prettierignore'));
        (new Filesystem)->copy(__DIR__ . '/../../../../.prettierrc', base_path('.prettierrc'));

        // shadcn components config
        (new Filesystem)->copy(__DIR__ . '/../../../../components.json', base_path('components.json'));

        // eslint config
        (new Filesystem)->copy(__DIR__ . '/../../../../eslint.config.js', base_path('eslint.config.js'));

        // pint config
        (new Filesystem)->copy(__DIR__ . '/../../../../pint.json', base_path('pint.json'));

        // rector config
        (new Filesystem)->copy(__DIR__ . '/../../../../rector.php', base_path('rector.php'));

        // tsconfig
        (new Filesystem)->copy(__DIR__ . '/../../../../tsconfig.json', base_path('tsconfig.json'));

        // vite.config.ts
        (new Filesystem)->delete(base_path('vite.config.js'));
        (new Filesystem)->copy(__DIR__ . '/../../../../vite.config.ts', base_path('vite.config.ts'));
    }
}
