<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Fixtures;

use Livewire\Attributes\Computed;

class TestComponentWithComputedProperties extends AbstractBaseComponent
{
    public function notAComputedProperty(): bool
    {
        return true;
    }

    #[Computed]
    private function privateMethod(): bool
    {
        return true;
    }

    #[Computed]
    protected function protectedMethod(): bool
    {
        $this->privateMethod();

        return true;
    }

    #[Computed]
    public function property(): bool
    {
        return true;
    }

    /** This is a comment. */
    #[Computed]
    public function propertyWithComments(): bool
    {
        return true;
    }

    /** @deprecated */
    #[Computed]
    public function deprecatedProperty(): bool
    {
        return true;
    }

    /** @deprecated Has a description. */
    #[Computed]
    public function deprecatedPropertyWithDescription(): bool
    {
        return true;
    }

    /** @return array<int,string> */
    #[Computed]
    public function propertyWithGenerics(): array
    {
        return ['foo', 'bar'];
    }

    /**
     * This is a comment.
     *
     * @return array<int,string>
     *
     * @deprecated Has a description.
     */
    public function getGetterStyleProperty(): array
    {
        return ['foo', 'bar'];
    }

    public function testIntegration(): string
    {
        return $this->privateMethod
            .$this->private_method
            .$this->protectedMethod
            .$this->protected_method
            .$this->property
            .$this->propertyWithComments
            .$this->property_with_comments
            .$this->deprecatedProperty
            .$this->deprecated_property
            .$this->deprecatedPropertyWithDescription
            .$this->deprecated_property_with_description
            .$this->propertyWithGenerics[0]
            .$this->property_with_generics[0]
            .$this->getterStyle[0]
            .$this->getter_style[0]
            .$this->traitMethod
            .$this->trait_method
            .$this->traitGetter
            .$this->trait_getter;
    }
}
