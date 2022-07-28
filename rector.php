<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {

    // get parameters
    $rectorConfig->parallel();
    $rectorConfig->importNames();

    $rectorConfig->paths([
        __DIR__ . '/src'
    ]);

    $rectorConfig->importNames();

    // define sets of rules
        $rectorConfig->sets([
            SymfonySetList::SYMFONY_54,
            SymfonySetList::SYMFONY_CODE_QUALITY,
            SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
            SetList::DEAD_CODE,
            SetList::CODE_QUALITY,
            SetList::CODING_STYLE,
            DoctrineSetList::DOCTRINE_CODE_QUALITY,
            LevelSetList::UP_TO_PHP_81
        ]);

    $rectorConfig->rule(ReturnTypeDeclarationRector::class);
};
