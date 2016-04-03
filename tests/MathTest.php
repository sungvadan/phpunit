<?php


class MathTest extends PHPUnit_Framework_TestCase
{
    public function testDouble()
    {
        $this->assertEquals(4, VTP\Math::double(2));
    }
    public function testDoubleIfZero()
    {
        $this->assertEquals(0, VTP\Math::double(0));
    }
}