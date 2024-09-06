<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Fixtures;

use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    use TraitWithComputedProperties;

    public function testIntegration(): string
    {
        return $this->traitMethod
            .$this->trait_method
            .$this->traitGetter
            .$this->trait_getter;
    }
}
