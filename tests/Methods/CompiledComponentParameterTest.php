<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Methods;

use CalebDW\LarastanLivewire\Methods\CompiledComponentParameter;
use PHPStan\Type\ArrayType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CompiledComponentParameterTest extends TestCase
{
    private CompiledComponentParameter $parameter;

    public function setUp(): void
    {
        parent::setUp();

        $this->parameter = new CompiledComponentParameter();
    }

    #[Test]
    public function itDescribesTheDataParameter(): void
    {
        $this->assertSame('data', $this->parameter->getName());
        $this->assertTrue($this->parameter->isOptional());
        $this->assertFalse($this->parameter->isVariadic());
        $this->assertTrue($this->parameter->passedByReference()->no());
    }

    #[Test]
    public function itAcceptsAndDefaultsToAnArray(): void
    {
        $this->assertInstanceOf(ArrayType::class, $this->parameter->getType());
        $this->assertInstanceOf(ArrayType::class, $this->parameter->getDefaultValue());
    }
}
