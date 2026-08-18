<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Methods;

use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;

/**
 * The single optional `$data` parameter shared by the compiled `view()`
 * method. `PHPStan\Reflection\Php\DummyParameter` would do the same job but
 * isn't covered by PHPStan's backward compatibility promise.
 */
final class CompiledComponentParameter implements ParameterReflection
{
    public function getName(): string
    {
        return 'data';
    }

    public function isOptional(): bool
    {
        return true;
    }

    public function getType(): Type
    {
        return new ArrayType(new MixedType(), new MixedType());
    }

    public function passedByReference(): PassedByReference
    {
        return PassedByReference::createNo();
    }

    public function isVariadic(): bool
    {
        return false;
    }

    public function getDefaultValue(): Type
    {
        return new ArrayType(new MixedType(), new MixedType());
    }
}
