<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Properties;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

final class ComputedPropertyExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (! $classReflection->is(Component::class)) {
            return false;
        }

        if ($classReflection->hasNativeMethod($this->getterPropertyName($propertyName))) {
            return true;
        }

        $camelPropertyName = Str::camel($propertyName);

        if (! $classReflection->hasNativeMethod($camelPropertyName)) {
            return false;
        }

        $methodReflection = $classReflection
            ->getNativeReflection()
            ->getMethod($camelPropertyName);

        return $methodReflection->getAttributes(Computed::class) !== [];
    }

    public function getProperty(
        ClassReflection $classReflection,
        string $propertyName,
    ): PropertyReflection {
        $getterPropertyName = $this->getterPropertyName($propertyName);

        if ($classReflection->hasNativeMethod($getterPropertyName)) {
            $methodName = $getterPropertyName;
        } else {
            $methodName = Str::camel($propertyName);
        }

        $methodReflection = $classReflection->getNativeMethod($methodName);

        $returnType = $methodReflection->getOnlyVariant()->getReturnType();

        return new ComputedProperty(
            declaringClass: $classReflection,
            methodReflection: $methodReflection,
            readableType: $returnType,
        );
    }

    protected function getterPropertyName(string $propertyName): string
    {
        return 'get'.Str::studly($propertyName).'Property';
    }
}
