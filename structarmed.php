<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Rule\Rules\Class_\MustBeFinalRule;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY())
    ->layer('Errors', 'src/Errors')
    ->layer('Methods', 'src/Methods')
    ->layer('Properties', 'src/Properties')
    ->ruleset([
        'Errors' => [],
        'Methods' => [],
        'Properties' => [],
    ])
    ->rule(
        'source_classes.must_be_final',
        new MustBeFinalRule(layer: 'Source')
    );
