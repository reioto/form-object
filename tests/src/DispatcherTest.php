<?php
namespace Tests;

use FormObject\Data;
use FormObject\StateBase;
use FormObject\Dispatcher;

class SampleData extends Data
{
    public $history;
}

class Base extends StateBase
{
    public function execute()
    {
        $data = $this->getData();
        $data->history[] = get_class($this);
    }
}

class Init extends Base
{
    protected $name = 'init';
    public function execute()
    {
        parent::execute();
        return __NAMESPACE__ . '\\First'; 
    }
}

class First extends Base
{
    protected $name = 'first';
    public function execute()
    {
        parent::execute();
        return __NAMESPACE__ . '\\Second'; 
    }
}

class Second extends Base
{
    protected $name = 'second';
    public function execute() 
    {
        parent::execute();
        return null;
    }
}

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group Dispatcher
     */
    function testDispatch_string()
    {
        $data = new SampleData;
        $state = new Init($data);
        $instance = new Dispatcher($state);
        $lastState = $instance->dispatch();

        $this->assertInstanceOf(
            __NAMESPACE__ . '\\Second', $lastState
        );

        $ext = array('Init', 'First', 'Second');
        $ext = array_map(function($row){
            return __NAMESPACE__ . '\\' . $row;
        }, $ext);
        
        $data = $lastState->getData();
        $this->assertEquals($ext, $data->history);
    }

    /**
     * @expectedException DomainException
     * @group Dispatcher
     */
    function testDispatch_string_not_state()
    {
        $data = new SampleData;
        $mock = $this->getMockBuilder('FormObject\\StateBase')
                     ->setConstructorArgs(array($data))
                     ->getMock();
        $mock->method('execute')
             ->will($this->returnCallback(function() {
                 return 'FormObject\\Data';
             }));
        $instance = new Dispatcher($mock);
        $instance->dispatch();

        $this->fail();
    }

    /**
     * @group Dispatcher
     */
    function testDispatch_state_object()
    {
        $data = new SampleData;
        $mock = $this->getMockBuilder('FormObject\\StateBase')
                     ->setConstructorArgs(array($data))
                     ->setMockClassName('ObFirst')
                     ->getMock();
        $mock->method('execute')
             ->will($this->returnCallback(function() use ($data) {
                 $data->history[] = 'test';
                 return new Second($data);
             }));
        $instance = new Dispatcher($mock);
        $lastState = $instance->dispatch();

        $ext = array('test', __NAMESPACE__ . '\\Second');

        $this->assertInstanceOf(end($ext), $lastState);

        $result = $lastState->getData();
        $this->assertSame($data, $result);
        $history = $result->history;
        $this->assertEquals($ext, $history);
    }

    /**
     * @expectedException DomainException
     * @group Dispatcher
     */
    function testDispatch_not_state()
    {
        $data = new SampleData;
        $mock = $this->getMockBuilder('FormObject\\StateBase')
                     ->setConstructorArgs(array($data))
                     ->getMock();
        $mock->method('execute')
             ->will($this->returnCallback(function() use ($data) {
                 return $data;
             }));
        $instance = new Dispatcher($mock);
        $instance->dispatch();

        $this->fail();
    }

    function dataProvider_testDispatch_return_value()
    {
        return array(
            array(array('sample')),
            array(true),
            array('sample'),
            array('false'),
            array(1),
        );
    }

    /**
     * @dataProvider dataProvider_testDispatch_return_value
     * @expectedException DomainException
     * @group Dispatcher
     */
    function testDispatch_return_value($ret)
    {
        $data = $this->getMockBuilder('FormObject\\Data')
                     ->getMock();
        $mock = $this->getMockBuilder('FormObject\\StateBase')
                     ->setConstructorArgs(array($data))
                     ->getMock();
        $mock->method('execute')
             ->will($this->returnCallback(function() use ($ret) {
                 return $ret;
             }));
        $instance = new Dispatcher($mock);
        $instance->dispatch();

        $this->fail();
    }
}