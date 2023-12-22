# Larastan-Livewire

A [Larastan](https://github.com/larastan/larastan) / [PHPStan](https://phpstan.org) extension for [Livewire](https://livewire.laravel.com/).


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
class ShowPost extends Component
{
    // Computed Property
    public function getPostProperty()
    {
        return Post::find($this->postId);
    }
```


## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

Larastan-Livewire is open-sourced software licensed under the [MIT license](LICENSE.md).
