<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\ReturnTypes;

use Livewire\Livewire;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\Type;

/**
 * The facade `@method` tag for `Livewire::test()` is non-generic, so the
 * `LivewireManager` stub never applies to the static call.
 */
final class LivewireTestReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function __construct(private TestableTypeResolver $testableTypeResolver)
    {
    }

    public function getClass(): string
    {
        return Livewire::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'test';
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope,
    ): ?Type {
        $args = $methodCall->getArgs();

        if ($args === []) {
            return null;
        }

        return $this->testableTypeResolver->fromName($scope->getType($args[0]->value));
    }
}
