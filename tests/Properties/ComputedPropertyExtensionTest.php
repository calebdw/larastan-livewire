<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Properties;

use CalebDW\LarastanLivewire\Properties\ComputedPropertyExtension;
use CalebDW\LarastanLivewire\Tests\stubs\TestComponentWithComputedProperties;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\Test;

final class ComputedPropertyExtensionTest extends PHPStanTestCase
{
    private ClassReflection $classReflection;
    private ComputedPropertyExtension $reflectionExtension;

    public function setUp(): void
    {
        parent::setUp();

        $this->classReflection = $this->createReflectionProvider()
            ->getClass(TestComponentWithComputedProperties::class);

        $this->reflectionExtension = new ComputedPropertyExtension();
    }

    #[Test]
    public function itDoesNotRegisterRegulerMethodAsComputedProperty(): void
    {
        $this->assertFalse($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'notAComputedProperty',
        ));
    }

    #[Test]
    public function itRegistersComputedProperyForAnyVisibility(): void
    {
        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'privateMethod',
        ));

        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'protectedMethod',
        ));

        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'property',
        ));
    }

    #[Test]
    public function itRegistersComputedProperyForGetterStyle(): void
    {
        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'getterStyle',
        ));
    }

    #[Test]
    public function itCanFindSnakeCaseProperties(): void
    {
        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'getter_style',
        ));
        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'private_method',
        ));
        $this->assertTrue($this->reflectionExtension->hasProperty(
            $this->classReflection,
            'protected_method',
        ));
    }

    #[Test]
    public function itReturnsDocComment(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'propertyWithComments',
        );

        $this->assertSame(
            $property->getDocComment(),
            '/** This is a comment. */',
        );
    }

    #[Test]
    public function itRespectsDeprecatedDoc(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'property',
        );
        $deprecatedProperty = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'deprecatedProperty',
        );

        $this->assertTrue($property->isDeprecated()->no());
        $this->assertTrue($deprecatedProperty->isDeprecated()->yes());
    }

    #[Test]
    public function itReturnsDeprecatedDescription(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'property',
        );
        $deprecatedProperty = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'deprecatedPropertyWithDescription',
        );

        $this->assertNull($property->getDeprecatedDescription());
        $this->assertSame(
            $deprecatedProperty->getDeprecatedDescription(),
            'Has a description.'
        );
    }

    #[Test]
    public function itReturnsType(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'property',
        );

        $this->assertSame(
            $property->getReadableType()->describe(VerbosityLevel::typeOnly()),
            'bool',
        );
    }

    #[Test]
    public function itReturnsGenericType(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'propertyWithGenerics',
        );

        $this->assertSame(
            $property->getReadableType()->describe(VerbosityLevel::typeOnly()),
            'array<int, string>',
        );
    }

    #[Test]
    public function itReturnsGetterStyleProperty(): void
    {
        $property = $this->reflectionExtension->getProperty(
            $this->classReflection,
            'getterStyle',
        );

        $this->assertSame(
            $property->getReadableType()->describe(VerbosityLevel::typeOnly()),
            'array<int, string>',
        );
        $this->assertSame(
            $property->getDeprecatedDescription(),
            'Has a description.'
        );
        $this->assertTrue($property->isDeprecated()->yes());
        $this->assertSame(
            $property->getDocComment(),
            <<<'TXT'
                /**
                 * This is a comment.
                 *
                 * @return array<int,string>
                 *
                 * @deprecated Has a description.
                 */
                TXT,
        );
    }
}
