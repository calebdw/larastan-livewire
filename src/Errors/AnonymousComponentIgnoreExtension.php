<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Errors;

use Livewire\Component;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Error;
use PHPStan\Analyser\IgnoreErrorExtension;
use PHPStan\Analyser\Scope;
use PHPStan\Node\NoopExpressionNode;
use PHPStan\Type\ObjectType;

final class AnonymousComponentIgnoreExtension implements IgnoreErrorExtension
{
    public function shouldIgnore(Error $error, Node $node, Scope $scope): bool
    {
        if ($error->getIdentifier() !== 'expr.resultUnused') {
            return false;
        }

        /** @phpstan-ignore phpstanApi.class (don't care) */
        if (! $node instanceof NoopExpressionNode) {
            return false;
        }

        /** @phpstan-ignore phpstanApi.method (don't care) */
        $originalExpr = $node->getOriginalExpr();

        if (! $originalExpr instanceof New_) {
            return false;
        }

        $class = $originalExpr->class;

        if (! $class instanceof Class_ || ! $class->isAnonymous()) {
            return false;
        }

        $extends = $class->extends;

        if ($extends === null) {
            return false;
        }

        $type = $scope->resolveTypeByName($extends);

        return (new ObjectType(Component::class))->isSuperTypeOf($type)->yes();
    }
}
