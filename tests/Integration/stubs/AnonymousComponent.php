<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Integration\stubs;

use Illuminate\View\View;
use Livewire\Component;

new class extends Component
{
    public function render(): View
    {
        return $this->view(['title' => 'Test']);
    }

    public function testCompiledMethods(): string
    {
        $this->placeholder();

        return $this->scriptModuleSrc()
            .$this->styleModuleSrc()
            .$this->globalStyleModuleSrc();
    }
};
