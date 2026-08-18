<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Methods;

use Illuminate\View\View;
use Livewire\Component;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;

/**
 * Livewire's Single File Component compiler injects these methods into the
 * anonymous class at compile time (see `Livewire\Compiler\Parser\Parser`).
 * They only exist in the compiled output, never in the source, so PHPStan
 * (which analyzes the source) reports them as undefined.
 *
 * @see https://github.com/livewire/livewire/blob/main/src/Compiler/Parser/Parser.php
 */
final class CompiledComponentMethodExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (! $classReflection->isAnonymous()) {
            return false;
        }

        if (! $classReflection->is(Component::class)) {
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

        return [
            'view' => new CompiledComponentMethod($classReflection, 'view', false, [
                $this->variant($viewType, [new CompiledComponentParameter()]),
            ]),
            'placeholder' => new CompiledComponentMethod($classReflection, 'placeholder', true, [
                $this->variant($viewType),
            ]),
            'scriptModuleSrc' => new CompiledComponentMethod($classReflection, 'scriptModuleSrc', true, [
                $this->variant($stringType),
            ]),
            'styleModuleSrc' => new CompiledComponentMethod($classReflection, 'styleModuleSrc', true, [
                $this->variant($stringType),
            ]),
            'globalStyleModuleSrc' => new CompiledComponentMethod($classReflection, 'globalStyleModuleSrc', true, [
                $this->variant($stringType),
            ]),
        ];
    }

    /** @param list<CompiledComponentParameter> $parameters */
    private function variant(ObjectType|StringType $returnType, array $parameters = []): FunctionVariant
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
