<?php

declare(strict_types=1);

namespace Suitcase\Builder\Tests;

use Suitcase\Builder\SDK;
use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    protected function buildSDK(string $url = 'https://api.example.com'): SDK
    {
        return SDK::make($url);
    }

    public function testResourcesCanBeAdded()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $this->assertContains('posts', $sdk->getResources()->pluck('name'));
    }

    public function testResourceCanSetEndpointFromNameIfNoneProvided()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $this->assertContains('posts', $sdk->getResources()->pluck('endpoint'));
    }

    public function testResourceSetsEndpointFromOptionsPassed()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts', [
            'endpoint' => 'blog-posts'
        ]);
        $this->assertContains('blog-posts', $sdk->getResources()->pluck('endpoint'));
    }

    public function testResourceCanSetAllowedMethodsIfNoneProvided()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $this->assertContains('get', $sdk->getResources()->pluck('allows')->flatten());
        $this->assertContains('find', $sdk->getResources()->pluck('allows')->flatten());
        $this->assertContains('create', $sdk->getResources()->pluck('allows')->flatten());
        $this->assertContains('update', $sdk->getResources()->pluck('allows')->flatten());
        $this->assertContains('delete', $sdk->getResources()->pluck('allows')->flatten());
    }
}
