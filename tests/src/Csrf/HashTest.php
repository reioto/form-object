<?php
namespace Tests\Csrf;

class HashTest extends \PHPUnit_Framework_TestCase
{
    private function getInstance()
    {
        return new \FormObject\Csrf\Hash('dummy');
    }

    function dataProvider_testMake()
    {
        $data = array();
        $data = array_map(function($num) {
            return array($num);
        }, range(0, 20));
        return $data;
    }

    /**
     * @dataProvider dataProvider_testMake
     * @group Csrf
     * @group Csrf_Hash
     */
    function testMake($length)
    {
        $instance = $this->getInstance();
        $hash = $instance::make($length);
        $this->assertEquals($length, strlen($hash));
    }

    /**
     * @group Csrf
     * @group Csrf_Hash
     */
    function testInstance()
    {
        $instance = $this->getInstance();
        $this->assertEquals($instance->getValue(), $instance);
        $this->assertEquals($instance->getValue(), $instance->__toString());
        $this->assertNotSame($instance->getValue(), $instance);
    }

    /**
     * @group Csrf
     * @group Csrf_Hash
     */
    function testClone()
    {
        $instance = $this->getInstance();
        $clone = clone $instance;
        $this->assertEquals($instance, $clone);
        $this->assertNotSame($instance, $clone);
    }


    /**
     * @group Csrf
     * @group Csrf_Hash
     */
    function testSerialize()
    {
        $instance = $this->getInstance();
        $str = serialize($instance);

        $this->assertNotEquals($str, $instance->getValue());

        $obj = unserialize($str);
        $this->assertEquals($obj->getValue(), $instance->getValue());
    }
}