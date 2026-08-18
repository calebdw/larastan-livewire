<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Methods;

use Illuminate\View\View;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ArrayType;
use PHPStan\Type\CallableType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

/**
 * Livewire registers these as macros on `Illuminate\View\View` to support
 * the fluent `$this->view()->title(...)->layout(...)` syntax used by page
 * components. The macro closures don't declare a return type, so without
 * this extension `->title()` (and friends) resolve to `mixed` instead of
 * `View`, even once `View::title()` itself is found.
 *
 * @see https://github.com/livewire/livewire/blob/main/src/Features/SupportPageComponents/SupportPageComponents.php
 */
final class ViewMacroMethodExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (! $classReflection->is(View::class)) {
            return false;
        }

        return array_key_exists($methodName, $this->methods($classReflection));
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return $this->methods($classReflection)[$methodName];
    }

    /** @return array<string, MethodReflection> */
    private function methods(ClassReflection $classReflection): array
    {
        $viewType = new ObjectType(View::class);
        $stringType = new StringType();
        $arrayType = new ArrayType(new StringType(), new MixedType());
        $emptyArray = new ArrayType(new StringType(), new MixedType());

        return [
            'layoutData' => new ViewMacroMethod($classReflection, 'layoutData', [
                $this->variant($viewType, [
                    new ViewMacroParameter('data', $arrayType, optional: true, defaultValue: $emptyArray),
                ]),
            ]),
            'section' => new ViewMacroMethod($classReflection, 'section', [
                $this->variant($viewType, [
                    new ViewMacroParameter('section', $stringType),
                ]),
            ]),
            'title' => new ViewMacroMethod($classReflection, 'title', [
                $this->variant($viewType, [
                    new ViewMacroParameter('title', $stringType),
                ]),
            ]),
            'slot' => new ViewMacroMethod($classReflection, 'slot', [
                $this->variant($viewType, [
                    new ViewMacroParameter('slot', $stringType),
                ]),
            ]),
            'extends' => new ViewMacroMethod($classReflection, 'extends', [
                $this->variant($viewType, [
                    new ViewMacroParameter('view', $stringType),
                    new ViewMacroParameter('params', $arrayType, optional: true, defaultValue: $emptyArray),
                ]),
            ]),
            'layout' => new ViewMacroMethod($classReflection, 'layout', [
                $this->variant($viewType, [
                    new ViewMacroParameter('view', $stringType),
                    new ViewMacroParameter('params', $arrayType, optional: true, defaultValue: $emptyArray),
                ]),
            ]),
            'response' => new ViewMacroMethod($classReflection, 'response', [
                $this->variant($viewType, [
                    new ViewMacroParameter('callback', new CallableType()),
                ]),
            ]),
        ];
    }

    /** @param list<ViewMacroParameter> $parameters */
    private function variant(Type $returnType, array $parameters): FunctionVariant
    {
        return new FunctionVariant(
            TemplateTypeMap::createEmpty(),
            null,
            $parameters,
            false,
            $returnType,
        );
    }
}
