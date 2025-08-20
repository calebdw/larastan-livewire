<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Errors;

use Livewire\Attributes\Computed;
use Livewire\Component;
use PhpParser\Node;
use PHPStan\Analyser\Error;
use PHPStan\Analyser\IgnoreErrorExtension;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;

final class UnusedComputedMethodIgnoreExtension implements IgnoreErrorExtension
{
    public function shouldIgnore(Error $error, Node $node, Scope $scope): bool
    {
        if ($error->getIdentifier() !== 'method.unused') {
            return false;
        }

        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return false;
        }

        if (! $classReflection->is(Component::class)) {
            return false;
        }

        preg_match('/::(.+)\(\)/', $error->getMessage(), $matches);

        if (! $this->isComputedMethod($classReflection, $matches[1])) {
            return false;
        }

        return true;
    }

    public function isComputedMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (preg_match('/^get.+Property$/', $methodName)) {
            return true;
        }

        $methodReflection = $classReflection
            ->getNativeReflection()
            ->getMethod($methodName);

        return $methodReflection->getAttributes(Computed::class) !== [];
    }
}
