<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Methods;

use CalebDW\LarastanLivewire\Methods\CompiledComponentMethodExtension;
use CalebDW\LarastanLivewire\Tests\Fixtures\TestComponentWithComputedProperties;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CompiledComponentMethodExtensionTest extends PHPStanTestCase
{
    private ClassReflection $classReflection;
    private CompiledComponentMethodExtension $reflectionExtension;

    public function setUp(): void
    {
        parent::setUp();

        $this->classReflection = $this->createReflectionProvider()
            ->getClass(TestComponentWithComputedProperties::class);

        $this->reflectionExtension = new CompiledComponentMethodExtension();
    }

    /**
     * The compiler only injects these methods into anonymous Single File
     * Components, never into named classes like this fixture. If this
     * extension claimed the methods here, it would shadow real
     * `method.notFound` errors on ordinary Livewire components.
     */
    #[Test]
    public function itDoesNotRegisterCompiledMethodsOnNamedComponents(): void
    {
        foreach (['view', 'placeholder', 'scriptModuleSrc', 'styleModuleSrc', 'globalStyleModuleSrc'] as $method) {
            $this->assertFalse($this->reflectionExtension->hasMethod($this->classReflection, $method));
        }
    }

    #[Test]
    public function itDoesNotRegisterUnrelatedMethodNames(): void
    {
        $this->assertFalse($this->reflectionExtension->hasMethod($this->classReflection, 'render'));
    }
}
