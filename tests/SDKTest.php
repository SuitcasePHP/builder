<?php

declare(strict_types=1);

namespace Suitcase\Builder\Tests;

use GuzzleHttp\Client;
use Suitcase\Builder\SDK;
use PHPUnit\Framework\TestCase;
use Suitcase\Builder\ParameterBag;
use Tightenco\Collect\Support\Collection;
use Suitcase\Builder\Exceptions\UnsupportedScheme;

class SDKTest extends TestCase
{
    protected function buildSDK(string $url = 'https://api.example.com'): SDK
    {
        return SDK::make($url);
    }

    public function testSdkCanBeCreated()
    {
        $this->assertInstanceOf(
            SDK::class,
            $this->buildSDK()
        );
    }

    public function testSdkCanGetTheCorrectScheme()
    {
        $sdk = $this->buildSDK();
        $this->assertEquals('https', $sdk->getScheme());
    }

    public function testSdkThrowsExceptionOnNotSupportedScheme()
    {
        $this->expectException(UnsupportedScheme::class);
        $sdk = $this->buildSDK('ftp://example.com');
    }

    public function testSdkCanGetTheCorrectHost()
    {
        $sdk = $this->buildSDK();
        $this->assertEquals('api.example.com', $sdk->getHost());
    }

    public function testSdkCanGetTheCorrectPath()
    {
        $sdk = $this->buildSDK('https://my-json-server.typicode.com/typicode/demo');
        $this->assertEquals('/typicode/demo', $sdk->getPath());
    }

    public function testSdkResourcesIsInstanceOfCollection()
    {
        $sdk = $this->buildSDK();
        $this->assertInstanceOf(
            Collection::class,
            $sdk->getResources()
        );
    }

    public function testSdkQueryIsInstanceOfParameterBag()
    {
        $sdk = $this->buildSDK();
        $this->assertInstanceOf(
            ParameterBag::class,
            $sdk->getQuery()
        );
    }

    public function testSdkClientIsInstanceOfGuzzleClient()
    {
        $sdk = $this->buildSDK();
        $this->assertInstanceOf(
            Client::class,
            $sdk->getClient()
        );
    }
}
