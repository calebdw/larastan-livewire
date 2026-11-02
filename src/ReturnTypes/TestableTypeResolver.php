<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\ReturnTypes;

use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

final class TestableTypeResolver
{
    public function __construct(private ReflectionProvider $reflectionProvider)
    {
    }

    public function fromName(Type $nameType): Type
    {
        if (! $this->testableIsGeneric()) {
            return new ObjectType(Testable::class);
        }

        return new GenericObjectType(Testable::class, [
            $this->componentType($nameType) ?? new ObjectType(Component::class),
        ]);
    }

    private function testableIsGeneric(): bool
    {
        if (! $this->reflectionProvider->hasClass(Testable::class)) {
            return false;
        }

        return $this->reflectionProvider
            ->getClass(Testable::class)
            ->getTemplateTypeMap()
            ->getTypes() !== [];
    }

    private function componentType(Type $nameType): ?Type
    {
        if ($nameType instanceof UnionType) {
            $types = [];

            foreach ($nameType->getTypes() as $inner) {
                $resolved = $this->componentType($inner);

                if ($resolved === null) {
                    return null;
                }

                $types[] = $resolved;
            }

            return TypeCombinator::union(...$types);
        }

        $component = new ObjectType(Component::class);

        if ($nameType->isClassString()->yes()) {
            $objectType = $nameType->getClassStringObjectType();

            if ($component->isSuperTypeOf($objectType)->yes()) {
                return $objectType;
            }
        }

        if ($nameType->isObject()->yes() && $component->isSuperTypeOf($nameType)->yes()) {
            return $nameType;
        }

        return null;
    }
}
