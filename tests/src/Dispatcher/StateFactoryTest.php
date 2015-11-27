<?php
namespace Tests\Dispatcher;

use FormObject\Data;
use FormObject\StateBase;
use FormObject\Dispatcher\StateFactory;

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

class StateFactoryTest extends \PHPUnit_Framework_TestCase
{
    private function getInstance()
    {
        $data = $this->getMockBuilder('FormObject\\Data')
                     ->setMockClassName('MockData')
                     ->getMock();
        return new StateFactory($data);
    }

    function dataProvider_testIsAccept()
    {
        $data = new SampleData;
        $initClass = __NAMESPACE__ . '\\Init';
        $sample = array();
        $sample[] = array(true, $initClass);
        $sample[] = array(true, new $initClass($data));
        $sample[] = array(false, __NAMESPACE__ . '\\SmapleData');
        $sample[] = array(false, $data);
        $sample[] = array(false, 'UnknownClass');
        $sample[] = array(false, true);
        $sample[] = array(false, 1);
        return $sample;
    }

    /**
     * @dataProvider dataProvider_testIsAccept
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testIsAccept($ext, $state)
    {
        $factory = $this->getInstance();
        $this->assertSame($ext, $factory->isAccept($state));
    }

    /**
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testFactory_string()
    {
        $state = __NAMESPACE__ . '\\Init';
        $data = new SampleData;
        $prev = new Init($data);
        $instance = new StateFactory($data);
        $result = $instance->factory($prev, $state);
        $this->assertSame($state, get_class($result));
        $this->assertSame($data, $result->getData());
    }

    /**
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testFactory_string_no_prev()
    {
        $state = __NAMESPACE__ . '\\Init';
        $data = new SampleData;
        $instance = new StateFactory($data);
        $result = $instance->factory(null, $state);
        $this->assertSame($state, get_class($result));
        $this->assertSame($data, $result->getData());
    }

    /**
     * @expectedException InvalidArgumentException
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testFactory_string_not_state()
    {
        $data = __NAMESPACE__ . '\\SampleData';
        $instance = $this->getInstance();
        $instance->factory(null, $data);

        $this->fail();
    }


    /**
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testFactory_object()
    {
        $data = new SampleData;
        $state = new Init($data);
        $instance = $this->getInstance();
        $result = $instance->factory(null, $state);
        $this->assertSame($state, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     * @group Dispatcher
     * @group Dispatcher_StateFactory
     */
    function testFactory_object_not_state()
    {
        $data = new SampleData;
        $instance = $this->getInstance();
        $instance->factory(null, $data);

        $this->fail();
    }
}