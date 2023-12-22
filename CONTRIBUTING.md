# CONTRIBUTING

Contributions are welcome, and are accepted via pull requests.
Please review these guidelines before submitting any pull requests.

## Process

1. Fork the project
1. Create a new branch
1. Code, test, commit and push
1. Open a pull request detailing your changes.

## Guidelines

- Please follow the [PSR-12 Coding Style Guide](http://www.php-fig.org/psr/psr-12/), enforced by [StyleCI](https://styleci.io/).
- Send a coherent commit history, making sure each individual commit in your pull request is meaningful.
- You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.
- Please remember that we follow [SemVer](http://semver.org/).

## Setup

Clone your fork, then install the dependencies:

```bash
    composer update
```

## Tests

Run all tests:


```bash
    composer test
```

Static analysis:

```bash
    composer test:phpstan
```

PHPUnit tests:

```bash
    composer test:phpunit
```
