<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Fixtures;

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

    public function testIntegration(): string
    {
        return $this->traitMethod
            .$this->trait_method
            .$this->traitGetter
            .$this->trait_getter;
    }
}
