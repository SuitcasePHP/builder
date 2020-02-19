<?php

declare(strict_types=1);

namespace Suitcase\Builder\Tests;

use Suitcase\Builder\SDK;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Suitcase\Builder\Exceptions\MethodNotAllowed;
use Suitcase\Builder\Exceptions\ResourceException;
use Suitcase\Builder\Exceptions\ResourceNotRegistered;

class ResourceCallTest extends TestCase
{
    protected function buildSDK(string $url = 'https://api.example.com'): SDK
    {
        return new SDK($url);
    }

    public function testResourceAvailable()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $this->assertContains('posts', $sdk->getResources()->pluck('name'));
    }

    public function testExceptionThrownIfResourceNotRegistered()
    {
        $this->expectException(ResourceNotRegistered::class);
        $sdk = $this->buildSDK();
        $sdk->use('fake');
    }

    public function testSdkIsReturnedWithSuccessfulResource()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $returned = $sdk->use('posts');
        $this->assertInstanceOf(SDK::class, $returned);
    }

    public function testResourceIsSetOnSdkAfterSelection()
    {
        $sdk = $this->buildSDK();
        $sdk->add('posts');
        $sdk->use('posts');

        $this->assertContains('posts', $sdk->getResource());
    }

    public function testExceptionIsThrownIfResourceDoesNotAllowAction()
    {
        $this->expectException(MethodNotAllowed::class);
        $sdk = $this->buildSDK();
        $sdk->add('posts', [
            'allows' => [
                'get'
            ]
        ]);
        $sdk->use('posts')->find('1');
        $sdk->use('posts')->create();
        $sdk->use('posts')->update();
        $sdk->use('posts')->delete();
    }

    public function testActionsCanBeRanOnTheActiveResource()
    {
        $sdk = $this->buildSDK('https://my-json-server.typicode.com/typicode/demo');
        $sdk->add('posts');
        $this->assertIsObject($sdk->use('posts')->get());
    }

    public function testActionReturnsAGuzzleResponse()
    {
        $sdk = $this->buildSDK('https://my-json-server.typicode.com/typicode/demo');
        $sdk->add('posts');
        $this->assertInstanceOf(
            Response::class,
            $sdk->use('posts')->get()
        );
    }

    public function testExceptionnThrownIfGuzzleFails()
    {
        $this->expectException(ResourceException::class);
        $sdk = $this->buildSDK('https://my-json-server.typicode.com/typicode/demo');
        $sdk->add('fake');
        $sdk->use('fake')->get();
    }
}
