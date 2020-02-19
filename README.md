# Suitcase Builder SDK

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple to use SDK builder for PHP. This package is still in beta - but there should be no breaking changes.

## Install

Via Composer

```bash
$ composer require suitcasephp/builder
```

## How to use

Building the SDK

```php
use Suitcase\Builder\SDK;

$sdk = new SDK('https://api.example.com');
```

Adding Resources to the SDK:

```php
$sdk->add('posts', [
    'endpoint' => 'posts',
    'allows' => [
        'get', 'find', 'create', 'update', 'delete'
    ]
]);
```

If you want to pass in a resource that follows the defaults:

```php
$sdk->add('posts');
```

What this will do is use the string passed in as a name and an endpoint, and pass through the default allows options - basically allowing all operations.

Selecting a resource is pretty simple:

```php
$sdk->use('posts');
```

What this will do is set the active resource on the SDK allowing you to use the allowed actions. A `MethodNotAllowed` is throw if the action is not registered in the `allows` array on the resource.

Performing actions on a resource:

```php
$sdk->use('posts')->get(); // return all posts
$sdk->use('posts')->find(1); // return the post with an identifier of 1
$sdk->use('posts')->create([]); // create a new post
$sdk->use('posts')->update(1, []); // update a post with an identifier of 1
$sdk->use('posts')->delete(1); // delet the post with a identifier of 1
```


[ico-version]: https://img.shields.io/packagist/v/suitcasephp/builder.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/suitcasephp/builder.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/SuitcasePHP/builder/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/SuitcasePHP/builder.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/SuitcasePHP/builder.svg?style=flat-square

[link-travis]: https://travis-ci.org/SuitcasePHP/builder
[link-scrutinizer]: https://scrutinizer-ci.com/g/SuitcasePHP/builder/code-structure
[link-packagist]: https://packagist.org/packages/suitcasephp/builder
[link-downloads]: https://packagist.org/packages/suitcasephp/builder
[link-author]: https://github.com/JustSteveKing
[link-code-quality]: https://scrutinizer-ci.com/g/SuitcasePHP/builder