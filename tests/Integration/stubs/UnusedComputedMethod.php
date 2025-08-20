<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Integration\stubs;

use Livewire\Attributes\Computed;
use Livewire\Component;

final class UnusedComputedMethod extends Component
{
    private function notAComputedProperty(): void
    {
    }

    #[Computed]
    private function computed(): void
    {
    }

    private function getComputedProperty(): void
    {
    }
}
