<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Integration\stubs;

use Illuminate\Contracts\Auth\Authenticatable;
use Livewire\Component;
use Livewire\Livewire;

final class TestableGenerics extends Component
{
    public function resolveFromClassString(Authenticatable $user): void
    {
        $this->assertThis(Livewire::actingAs($user)->test(self::class)->instance());
        $this->assertThis(Livewire::withQueryParams(['page' => 1])->test(self::class)->instance());
        $this->assertThis(Livewire::test(self::class)->instance());
    }

    public function resolveFromInstance(Authenticatable $user): void
    {
        $this->assertThis(Livewire::actingAs($user)->test($this)->instance());
        $this->assertThis(Livewire::test($this)->instance());
    }

    public function allowsStringComponentNames(Authenticatable $user, string $name): void
    {
        Livewire::actingAs($user)->test($name);
        Livewire::test($name);
    }

    private function assertThis(self $component): void
    {
    }
}
