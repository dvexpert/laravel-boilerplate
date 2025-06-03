<?php

namespace Boilerplate\Console\Commands\Traits;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Namespace_;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use PhpParser\Node\{Arg, Identifier, Name};
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use PhpParser\Node\Stmt\{Expression, Nop, UseUse, Use_};
use Boilerplate\Console\Commands\BoilerplateInstallCommand;
use PhpParser\Node\Expr\{ArrayItem, Array_, FuncCall, StaticCall};
use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract, ParserFactory, PhpVersion};

use function Laravel\Prompts\warning;

trait SetupAppProviders
{
    private function configureAppServiceProvider()
    {
        /** @var BoilerplateInstallCommand $this */
        warning('Configuring app service provider...');

        $this->doAppend();
        $this->doLint();
    }

    private function doAppend()
    {
        $code = (new Filesystem)->get(base_path('app/Providers/AppServiceProvider.php'));

        $parser = (new ParserFactory)->createForVersion(PhpVersion::fromString('8.3'));
        $stmts  = $parser->parse($code);

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new class extends NodeVisitorAbstract
        {
            public $shouldAddUse = false;

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Stmt\ClassMethod && $node->name->toString() === 'register') {
                    $alreadyInjected = false;
                    foreach ($node->stmts as $stmt) {
                        if ($stmt instanceof Expression && $stmt->expr instanceof StaticCall) {
                            $call = $stmt->expr;
                            if (
                                $call->class instanceof Name && $call->class->toString() === 'Inertia' && $call->name instanceof Identifier && $call->name->toString() === 'share'
                            ) {
                                $alreadyInjected = true;
                                break;
                            }
                        }
                    }

                    if (! $alreadyInjected) {
                        $shareCall = new Expression(new StaticCall(
                            new Name('Inertia'),
                            'share',
                            [
                                new Arg(new String_('app')),
                                new Arg(new Array_([
                                    new ArrayItem(
                                        new FuncCall(new Name('config'), [
                                            new Arg(new String_('app.name')),
                                        ]),
                                        new String_('name')
                                    ),
                                ])),
                            ]
                        ));
                        $node->stmts[] = new Nop;
                        $node->stmts[] = $shareCall;
                        $node->stmts[] = new Nop;

                        $this->shouldAddUse = true;
                    }
                }
            }

            public function afterTraverse(array $nodes)
            {
                if (! ($this->shouldAddUse ?? false)) {
                    return $nodes;
                }

                $hasImport = false;
                foreach ($nodes as $stmt) {
                    if ($stmt instanceof Use_) {
                        foreach ($stmt->uses as $use) {
                            if ($use->name->toString() === 'Inertia\\Inertia') {
                                $hasImport = true;
                                break 2;
                            }
                        }
                    }
                }

                if (! $hasImport) {
                    foreach ($nodes as $index => $stmt) {
                        if ($stmt instanceof Namespace_) {
                            // Insert use inside the namespace block, at the top
                            array_unshift($nodes[$index]->stmts, new Use_([
                                new UseUse(new Name('Inertia\\Inertia')),
                            ]));
                            break;
                        }
                    }
                }

                return $nodes;
            }
        });

        $modified = $traverser->traverse($stmts);
        $newCode  = (new PrettyPrinter)->prettyPrintFile($modified);
        (new Filesystem)->put(base_path('app/Providers/AppServiceProvider.php'), $newCode);
    }

    private function doLint()
    {
        /** @var BoilerplateInstallCommand $this */
        Process::timeout(0)
            ->path(base_path())
            ->run(
                [$this->pintBinary(), base_path('app/Providers/AppServiceProvider.php')],
                function (string $type, string $output) {
                    $this->output->write($output);
                }
            );
    }
}
