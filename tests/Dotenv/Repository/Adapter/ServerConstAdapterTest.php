<?php

declare(strict_types=1);

namespace Dotenv\Tests\Repository\Adapter;

use Dotenv\Repository\Adapter\ServerConstAdapter;
use PHPUnit\Framework\TestCase;

final class ServerConstAdapterTest extends TestCase
{
    public function testGoodRead()
    {
        $_SERVER['CONST_TEST'] = 'foo bar baz';
        $value = self::createAdapter()->read('CONST_TEST');
        self::assertTrue($value->isDefined());
        self::assertSame('foo bar baz', $value->get());
    }

    public function testFalseRead()
    {
        $_SERVER['CONST_TEST'] = false;
        $value = self::createAdapter()->read('CONST_TEST');
        self::assertTrue($value->isDefined());
        self::assertSame('false', $value->get());
    }

    public function testTrueRead()
    {
        $_SERVER['CONST_TEST'] = true;
        $value = self::createAdapter()->read('CONST_TEST');
        self::assertTrue($value->isDefined());
        self::assertSame('true', $value->get());
    }

    public function testBadTypeRead()
    {
        $_SERVER['CONST_TEST'] = [123];
        $value = self::createAdapter()->read('CONST_TEST');
        self::assertFalse($value->isDefined());
    }

    public function testUndefinedRead()
    {
        unset($_SERVER['CONST_TEST']);
        $value = self::createAdapter()->read('CONST_TEST');
        self::assertFalse($value->isDefined());
    }

    public function testGoodWrite()
    {
        self::assertTrue(self::createAdapter()->write('CONST_TEST', 'foo'));
        self::assertSame('foo', $_SERVER['CONST_TEST']);
    }

    public function testGoodBoolFalseWrite()
    {
        self::assertTrue(self::createAdapter()->write('CONST_TEST', false));
        self::assertIsBool($_SERVER['CONST_TEST']);
        self::assertFalse($_SERVER['CONST_TEST']);
    }

    public function testGoodBoolTrueeWrite()
    {
        self::assertTrue(self::createAdapter()->write('CONST_TEST', true));
        self::assertIsBool($_SERVER['CONST_TEST']);
        self::assertTrue($_SERVER['CONST_TEST']);
    }

    public function testBadBoolFalseWrite()
    {
        self::assertTrue(self::createAdapter()->write('CONST_TEST', 'false'));
        self::assertIsNotBool($_SERVER['CONST_TEST']);
    }

    public function testEmptyWrite()
    {
        self::assertTrue(self::createAdapter()->write('CONST_TEST', ''));
        self::assertSame('', $_SERVER['CONST_TEST']);
    }

    public function testGoodDelete()
    {
        self::assertTrue(self::createAdapter()->delete('CONST_TEST'));
        self::assertFalse(isset($_SERVER['CONST_TEST']));
    }

    /**
     * @return \Dotenv\Repository\Adapter\AdapterInterface
     */
    private static function createAdapter()
    {
        return ServerConstAdapter::create()->get();
    }
}
