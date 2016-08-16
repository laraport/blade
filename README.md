Laraport Blade [![Build Status](https://travis-ci.org/laraport/blade.svg?branch=master)](https://travis-ci.org/laraport/blade)
======
This php library is an unofficial port of the [laravel](http://laravel.com/) (L4) blade templating engine. See [illuminate/blade](https://github.com/illuminate/view/tree/4.2) and the [docs](https://laravel.com/docs/4.2/templates) for more details. The reason for this port is so that developers may consume it in a standalone project or another framework without being forced to import the whole laravel framework.

> Requires PHP 5.4 or greater.

Includes a few extras:
- Supports raw template string rendering.
- Supports custom file extensions.
- Cache path is optional.

# Table of contents

- [Table of contents](#table-of-contents)
- [Install](#install)
- [Usage](#usage)
	- [The laravel way](#the-laravel-way)
	- [Without cache](#without-cache)
	- [Raw string templates](#raw-string-templates)
	- [Custom file extensions](#custom-file-extensions)
	- [Shared data](#shared-data)
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

#### The laravel way

The usual laravel way is setting the view path and cache.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$path2views = __DIR__.'/path/to/views';
$path2cache = __DIR__.'/path/to/cache';

$Blade = new Laraport\Blade($path2views, $path2cache);

$View = $Blade->make('welcome', ['name' => 'Alice']);

echo $View->render();

```

> which will render `__DIR__./path/to/views/welcome{.blade}.php`

#### Without cache

One of the added feature is loading a view without setting up a cache directory. This uses [vfsStream](https://github.com/mikey179/vfsStream) behind the scenes.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$Blade = new Laraport\Blade(__DIR__.'/path/to/views');

$View = $Blade->make('hello', ['name' => 'Bob']);

echo $View->render();

```

> which will render `__DIR__./path/to/views/hello{.blade}.php` without any cache.

#### Raw string templates

Another added feature is supporting raw string template rendering. This also uses [vfsStream](https://github.com/mikey179/vfsStream) behind the scenes.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$View = Laraport\Blade::render('Hello {{ $name }}!', ['name' => 'Eve']);

echo $View->render();

```

> which will print out `Hello Eve!`

#### Custom file extensions

You may also set custom file extensions for your blade view templates.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$Blade = new Laraport\Blade(__DIR__.'/path/to/views');

$Blade->withExtension('foo.bar');

$View = $Blade->make('custom', ['name' => 'Laravel']);

echo $View->render();

```

> Which will render one of `custom.foo.bar`, `custom.blade.php` or `custom.php`, whichever is found.

> You may add more than one extension if you so wish.

#### Shared data

Data may also be shared as globals for all your views.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$Blade = new Laraport\Blade(__DIR__.'/path/to/views');

$Blade->share('acme', 'baz');

$View = $Blade->make('foo');

echo $View->render();

```

> `$acme` will be available to all views.

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

- [Torch](https://github.com/mattstauffer/Torch/tree/4.2)
- [philo/laravel-blade](https://github.com/PhiloNL/Laravel-Blade)
- [50onred/laravel-blade](https://github.com/spatie/laravel-blade)

# License

Copyright (c) Kamal Khan.
Released under the [MIT License](http://opensource.org/licenses/MIT).
