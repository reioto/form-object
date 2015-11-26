<?php
namespace Tests;

use FormObject\StateBase;

class Input extends StateBase
{
    protected $name = 'input';

    public function execute()
    {
        $data = $this->getData();
        if ($data->state !== 'input') return 'input';
        $data->state = 'confirm';
        $data->history[] = $this->getName();
        
        return 'confirm';
    }
}

class SampleDefault extends StateBase 
{
    public function execute(){}
}

class StateBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group StateBase
     */
    function testGetName_no_name()
    {
        $data = $this->getMockBuilder('FormObject\\Data')
                     ->getMock();
        $form = new SampleDefault($data);

        $ext = __NAMESPACE__ . '\\SampleDefault';
        $this->assertEquals($ext, $form->getName());
    }

    /**
     * @group StateBase
     */
    function testGetName_set_name()
    {
        $data = $this->getMockBuilder('FormObject\\Data')
                     ->getMock();
        $form = new Input($data);
        $this->assertEquals('input', $form->getName());
    }
}