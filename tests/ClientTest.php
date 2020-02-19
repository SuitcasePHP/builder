<?php

declare(strict_types=1);

namespace Suitcase\Builder\Tests;

use Suitcase\Builder\SDK;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    protected function buildSDK(string $url = 'https://my-json-server.typicode.com/typicode/demo'): SDK
    {
        return new SDK($url);
    }

    public function testClientReturnsHttpOkOnAllResources()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $response = $sdk->use('posts')->get();
        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
    }

    public function testClientCanFindResource()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $response = $sdk->use('posts')->find(1);
        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
        $this->assertNotNull($response->getBody()->getContents());
    }

    public function testClientCanCreateResource()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $response = $sdk->use('posts')->create([
            'title' => 'Test Title',
            'description' => 'This is a test description'
        ]);
        $this->assertEquals(
            201,
            $response->getStatusCode()
        );
    }

    public function testClientCanUpdateResource()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $response = $sdk->use('posts')->update(1, [
            'title' => 'Test Title'
        ]);
        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
    }

    public function testClientCanDeleteResource()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $response = $sdk->use('posts')->delete(1);
        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
    }
}
