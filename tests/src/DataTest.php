<?php
namespace Tests;

use FormObject\Data as Data;

class SampleCls extends Data
{
    const NAME = 'sample';

    public $name = 'sample';
    protected $protect = 'protectsample';
    private $private = 'privatesample';

    public static $static_name = 'static_name';
    protected static $static_protect_name = 'static_protect_name';
    private static $static_private_name = 'static_private_name';
}

class SampleCls2 extends Data
{
    public $sample2 = 'sample2';
}

class SampleCls3 extends Data
{
    public $sample2 = 'sample2';
}

class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group Data
     */
    function testToArray()
    {
        $instance = new SampleCls();
        $result = $instance->toArray();

        $ext = array(
            'name' => 'sample'
        );
        $this->assertEquals($ext, $result);
    }

    /**
     * @group Data
     */
    function testToArray_no_has_static()
    {
        $instance = new SampleCls2();
        $result = $instance->toArray();

        $ext = array(
            'sample2' => 'sample2'
        );
        $this->assertEquals($ext, $result);
    }

    /**
     * @group Data
     */
    function testGetKeys()
    {
        $instance = new SampleCls();
        $result = $instance->getKeys();

        $ext = array(
            'name'
        );
        $this->assertEquals($ext, $result);
    }

    /**
     * @group Data
     */
    function testGetKeys_static_cache()
    {
        $instance = new SampleCls();
        $result = $instance->getKeys();

        $ext = array(
            'name'
        );

        $this->assertEquals($ext, $result);

        $instance = new SampleCls2();
        $result = $instance->getKeys();

        $ext = array(
            'sample2'
        );

        $this->assertEquals($ext, $result);
    }

    /**
     * @group Data
     */
    function testIterate()
    {
        $instance = new SampleCls();
        $result = array();

        foreach ($instance as $key => $val) {
            $result[$key] = $val;
        }

        $ext = array(
            'name' => 'sample'
        );

        $this->assertEquals($ext, $result);
    }

    /**
     * @group Data
     */
    function testArrayAccess_getset()
    {
        $instance = new SampleCls();
        $ext = 'overwrite';

        $instance['name'] = $ext;
        $this->assertEquals($ext, $instance['name']);
    }

    /**
     * @expectedException OutOfBoundsException
     * @group Data
     */
    function testArrayAccess_set_unknown()
    {
        $instance = new SampleCls();
        $instance['unknown'] = 'val';

        $this->fail();
    }

    /**
     * @expectedException OutOfBoundsException
     * @group Data
     */
    function testArrayAccess_get_unknown()
    {
        $instance = new SampleCls();
        $test = $instance['unknown'];

        $this->fail();
    }


    /**
     * @group Data
     */
    function testArrayAccess_exists()
    {
        $instance = new SampleCls();
        $this->assertTrue(isset($instance['name']));
        $this->assertFalse(isset($instance['unknown']));
    }

    /**
     * @group Data
     */
    function testCount()
    {
        $instance = new SampleCls();
        $this->assertSame(1, count($instance));
    }

    /**
     * @group Data
     */
    function testSerialize()
    {
        $instance = new SampleCls();
        $instance->name = 'overwrite';

        $seri_str = serialize($instance);
        $desirialize = unserialize($seri_str);

        $this->assertEquals(get_class($instance), get_class($desirialize));
        $this->assertEquals($instance->toArray(), $desirialize->toArray());
    }

    /**
     * @group Data
     */
    function testBind()
    {
        $instance = new SampleCls();
        $input = array('name' => 'overwrite', 'unknown' => 'action');
        $ret = $instance->bind($input);
        $this->assertEquals(get_class($instance), get_class($ret));

        $ext = array( 'name' => $input['name'] );
        $this->assertEquals($ext, $instance->toArray());
    }

    /**
     * @group Data
     */
    function testBind_replace()
    {
        $instance = new SampleCls();
        $input = array('foo' => 'overwrite');
        $replace = array('foo' => 'name');
        $ext = array('name' => 'overwrite');
        $instance->bind($input, $replace);

        $this->assertEquals($ext, $instance->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @group Data
     */
    function testBind_replace_input_unknownKey()
    {
        $instance = new SampleCls();
        $input = array('foo' => 'overwrite');
        $replace = array('unknown' => 'name');
        $instance->bind($input, $replace);

        $this->fail();
    }


}