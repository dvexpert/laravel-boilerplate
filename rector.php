<?php

use Rector\Config\RectorConfig;
use RectorLaravel\Set\{LaravelLevelSetList, LaravelSetList};
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use RectorLaravel\Rector\Expr\AppEnvironmentComparisonToParameterRector;
use RectorLaravel\Rector\Coalesce\ApplyDefaultInsteadOfNullCoalesceRector;
use RectorLaravel\Rector\ClassMethod\{AddGenericReturnTypeToRelationsRector};
use RectorLaravel\Rector\StaticCall\{EloquentMagicMethodToQueryBuilderRector, RouteActionCallableRector};
use RectorLaravel\Rector\Class_\{
    AnonymousMigrationsRector,
    LivewireComponentQueryStringToUrlAttributeRector
};
use RectorLaravel\Rector\Expr\SubStrToStartsWithOrEndsWithStaticMethodCallRector\SubStrToStartsWithOrEndsWithStaticMethodCallRector;
use RectorLaravel\Rector\FuncCall\{HelperFuncCallToFacadeClassRector, RemoveDumpDataDeadCodeRector, SleepFuncToSleepStaticCallRector};
use RectorLaravel\Rector\ArrayDimFetch\{
    RequestVariablesToRequestFacadeRector,
    ServerVariableToRequestFacadeRector,
    SessionVariableToSessionFacadeRector
};
use RectorLaravel\Rector\MethodCall\{
    AssertStatusToAssertMethodRector,
    // EloquentOrderByToLatestOrOldestRector,
    EloquentWhereRelationTypeHintingParameterRector,
    EloquentWhereTypeHintClosureParameterRector,
    RedirectBackToBackHelperRector,
    ResponseHelperCallToJsonResponseRector,
    UseComponentPropertyWithinCommandsRector,
    ValidationRuleArrayStringValueToArrayRector,
    WhereToWhereLikeRector
};

// use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
        __DIR__ . '/database',
    ])
    ->withSkip([
        __DIR__ . '/config/pulse.php',
        __DIR__ . '/config/telescope.php',
        RenameParamToMatchTypeRector::class => [
            __DIR__ . '/database/migrations',
        ],
    ])
    ->withSkipPath(__DIR__ . '/bootstrap/cache')
    ->withPhpSets(php84: true)
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_IF_HELPERS,
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
    ])
    // register single rule
    ->withRules([
        //     TypedPropertyFromStrictConstructorRector::class
        AddGenericReturnTypeToRelationsRector::class,
        AnonymousMigrationsRector::class,
        AppEnvironmentComparisonToParameterRector::class,
        ApplyDefaultInsteadOfNullCoalesceRector::class,
        AssertStatusToAssertMethodRector::class,
        EloquentMagicMethodToQueryBuilderRector::class,
        // EloquentOrderByToLatestOrOldestRector::class,
        EloquentWhereRelationTypeHintingParameterRector::class,
        EloquentWhereTypeHintClosureParameterRector::class,
        HelperFuncCallToFacadeClassRector::class,
        LivewireComponentQueryStringToUrlAttributeRector::class,
        RedirectBackToBackHelperRector::class,
        RemoveDumpDataDeadCodeRector::class,
        RequestVariablesToRequestFacadeRector::class,
        ResponseHelperCallToJsonResponseRector::class,
        RouteActionCallableRector::class,
        ServerVariableToRequestFacadeRector::class,
        SessionVariableToSessionFacadeRector::class,
        SleepFuncToSleepStaticCallRector::class,
        SubStrToStartsWithOrEndsWithStaticMethodCallRector::class,
        UseComponentPropertyWithinCommandsRector::class,
        ValidationRuleArrayStringValueToArrayRector::class,
        WhereToWhereLikeRector::class,
        // \Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter\FullyQualifiedNameClassNameImportSkipVoter::class
    ])
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        naming: true,
        typeDeclarations: true,
        carbon: true,
    )
    ->withImportNames(
        importDocBlockNames: true,
        importShortClasses: false
    );
// ->withConfiguredRule(FullyQualifiedNameClassNameImportSkipVoter::class)
