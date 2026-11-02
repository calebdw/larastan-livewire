<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\ReturnTypes;

use Livewire\LivewireManager;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

final class LivewireManagerTestReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(private TestableTypeResolver $testableTypeResolver)
    {
    }

    public function getClass(): string
    {
        return LivewireManager::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'test';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        $args = $methodCall->getArgs();

        if ($args === []) {
            return null;
        }

        return $this->testableTypeResolver->fromName($scope->getType($args[0]->value));
    }
}
