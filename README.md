<div align="center">
  <p>
    <img src="/art/larastan_livewire.jpg" alt="Larastan Livewire" width="60%">
  </p>
  <p>A <a href="https://github.com/larastan/larastan">Larastan</a> / <a href="https://phpstan.org">PHPStan</a> extension for <a href="https://livewire.laravel.com/">Laravel Livewire</a>.</p>
  <p>
    <a href="https://packagist.org/packages/calebdw/larastan-livewire"><img src="https://badge.laravel.cloud/badge/calebdw/larastan-livewire" alt="Laravel Compatibility"></a>
    <a href="https://github.com/calebdw/larastan-livewire/actions/workflows/tests.yml"><img src="https://github.com/calebdw/larastan-livewire/actions/workflows/tests.yml/badge.svg" alt="Test Results"></a>
    <a href="https://github.com/calebdw/larastan-livewire"><img src="https://img.shields.io/github/license/calebdw/larastan-livewire" alt="License"></a>
    <a href="https://packagist.org/packages/calebdw/larastan-livewire"><img src="https://img.shields.io/packagist/v/calebdw/larastan-livewire.svg" alt="Packagist Version"></a>
    <a href="https://packagist.org/packages/calebdw/larastan-livewire"><img src="https://img.shields.io/packagist/dt/calebdw/larastan-livewire.svg" alt="Total Downloads"></a>
  </p>
</div>

## Install

```bash
composer require calebdw/larastan-livewire --dev
```

If you have the [PHPStan extension installer](https://phpstan.org/user-guide/extension-library#installing-extensions) installed then nothing more is needed, otherwise you will need to manually include the extension in the `phpstan.neon(.dist)` configuration file:

```neon
includes:
    - ./vendor/calebdw/larastan-livewire/extension.neon
```

## Features

### Computed Properties

[Computed properties](https://livewire.laravel.com/docs/computed-properties) are properly resolved from methods that have the `Computed` attribute applied.

```php
<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowPost extends Component
{
    #[Computed]
    public function post()
    {
        return Post::find($this->postId);
    }
}
```

#### Getter Style

The older, ["getter" style attributes](https://laravel-livewire.com/docs/2.x/properties#computed-properties) are supported as well:

```php
<?php

use Livewire\Component;

class ShowPost extends Component
{
    // Computed Property
    public function getPostProperty()
    {
        return Post::find($this->postId);
    }
}
```

### Page Component View Macros

The fluent `title`/`layout`/etc. methods Livewire adds to `Illuminate\View\View` for
[page components](https://livewire.laravel.com/docs/4.x/pages) are resolved and properly typed:

```php
<?php

use Livewire\Component;

class ShowPost extends Component
{
    public function render()
    {
        return view('livewire.show-post')
            ->title($this->post->title)
            ->layout('layouts::posts');
    }
}
```

## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

Larastan-Livewire is open-sourced software licensed under the [MIT license](LICENSE).
