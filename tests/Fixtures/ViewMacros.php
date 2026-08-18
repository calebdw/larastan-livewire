<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Fixtures;

use Illuminate\View\View;

final class ViewMacros
{
    public function testIntegration(View $view): View
    {
        return $view
            ->layoutData(['foo' => 'bar'])
            ->section('content')
            ->title('Create Post')
            ->slot('main')
            ->extends('layouts.app')
            ->layout('layouts::dashboard', ['title' => 'Posts'])
            ->response(fn () => null);
    }
}
