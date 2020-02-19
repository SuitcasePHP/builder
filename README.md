# Suitcase Builder SDK

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

A simple to use SDK builder for PHP. This package is still in beeta - but there should be no breaking changes.

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

[link-packagist]: https://packagist.org/packages/suitcasephp/builder
[link-downloads]: https://packagist.org/packages/suitcasephp/builder
[link-author]: https://github.com/JustSteveKing