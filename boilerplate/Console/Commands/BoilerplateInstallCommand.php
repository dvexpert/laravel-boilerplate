<?php

namespace Boilerplate\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\warning;

class BoilerplateInstallCommand extends Command
{
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
        warning('TODO: Boilerplate Install Command.');
    }
}
