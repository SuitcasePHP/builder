<?php

declare(strict_types=1);

namespace Suitcase\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Suitcase\Builder\ParameterBag;

class ParameterBagTest extends TestCase
{
    public function buildParameterBag(array $parameters = [])
    {
        return new ParameterBag($parameters);
    }

    public function testParameterBagCanBeCreated()
    {
        $this->assertInstanceOf(
            ParameterBag::class,
            $this->buildParameterBag()
        );
    }

    public function testCanGetParameterFromParameterBag()
    {
        $bag = $this->buildParameterBag([
            'foo' => 'bar'
        ]);

        $this->assertEquals(
            'bar',
            $bag->get('foo')
        );
    }

    public function testCanCheckIfParameterBagHasItem()
    {
        $bag = $this->buildParameterBag([
            'foo' => 'bar'
        ]);

        $this->assertTrue($bag->has('foo'));
    }

    public function testCanGetAllParametersFromBag()
    {
        $bag = $this->buildParameterBag($parameters = [
            'foo' => 'bar'
        ]);

        $this->assertEquals($parameters, $bag->all());
    }

    public function testCanSetItemOnParameterBag()
    {
        $bag = $this->buildParameterBag();
        $this->assertEquals([], $bag->all());
        $bag->set('foo', 'bar');
        $this->assertTrue($bag->has('foo'));
    }

    public function testCanRemoveItemFromParameterBag()
    {
        $bag = $this->buildParameterBag($parameters = [
            'foo' => 'bar'
        ]);
        $this->assertTrue($bag->has('foo'));
        $bag->remove('foo');
        $this->assertEquals([], $bag->all());
    }
}
