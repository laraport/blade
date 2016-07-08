Laraport Blade [![Build Status](https://travis-ci.org/laraport/blade.svg?branch=master)](https://travis-ci.org/laraport/blade)
======
This php library is an unofficial port of [laravel](http://laravel.com/) blade templating engine. See [illuminate/blade](https://github.com/illuminate/view/tree/4.2) and the [docs](https://laravel.com/docs/4.2/templates) for more details. The reason for this port is to consume it outside of laravel in a standalone project or another framework, where you find it tediuos to import the whole laravel framework.

> Requires PHP 5.4 or greater.

# Table of contents

- [Install](#install)
- [Usage](#usage)
    - [Foo bar](#foo-bar)
- [Test](#test)
- [Similar projects](#similar-projects)
- [License](#license)

# Install

This package can be installed by requiring it via [composer](https://getcomposer.org).

```shell
$ composer require laraport/blade
```

# Usage

If you have consumed blade views in laravel (which i am sure you have), its now a cinch to consume it in a standalone project as well.

#### Foo bar

Lorem ipsum dolor sit amet...

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$Blade = new Laraport\Blade;
// ...

```

# Test
> *First make sure you are in the project source directory.*

Do a composer install.
```shell
$ composer install
```
Run the tests.
```shell
$ vendor/bin/phpunit
```
or
```shell
$ composer test
```

# Similar projects

- [example/example](https://github.com/example/example)

# License

Copyright (c) Kamal Khan.
Released under the [MIT License](http://opensource.org/licenses/MIT).
