<?php
namespace Tests\Csrf\Storage;

use FormObject\Csrf\Storage\Callback;

class CallbackTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @group Csrf
     * @group Csrf_Storage
     * @group Csrf_Storage_Callback
     */
    function testConstruct()
    {
        $saved = new \ArrayObject();
        $obj = new CallBack(
            function($str) use ($saved) { $saved[0] = $str; },
            function() use ($saved) { return $saved[0]; }
        );

        $ext = 'tempword';
        $obj->save($ext);
        $this->assertEquals($ext, $obj->load());
        $this->assertEquals($ext, $saved[0]);

        $saved[0] = 'overwrite';
        $this->assertEquals($saved[0], $obj->load());
    }

    /**
     * @group Csrf
     * @group Csrf_Storage
     * @group Csrf_Storage_Callback
     */
    function testClear()
    {
        $saved = new \ArrayObject();
        $obj = new Callback(
            function($str) use ($saved) { $saved[0] = $str; },
            function() use ($saved) { return $saved[0]; }
        );

        $ext = 'tempword';
        $obj->save($ext);
        $obj->clear();
        $this->assertEquals(null, $saved[0]);
    }

}