<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\stubs;

use Livewire\Attributes\Computed;

trait TraitWithComputedProperties
{
    #[Computed]
    private function traitMethod(): bool
    {
        return true;
    }

    public function getTraitGetterProperty(): bool
    {
        return true;
    }
}
