<?php
namespace FormObject;

use FormObject\StateBase as State;
use FormObject\Dispatcher\IStateFactory;
use FormObject\Dispatcher\StateFactory;

class Dispatcher implements IDispatcher
{
    private $state;
    function __construct(State $state)
    {
        $this->setState($state);
    }

    /**
     * @param State $state
     */
    protected function setState(State $state)
    {
        $this->state = $state;
    }

    public function getState() { return $this->state; }


    private $_stateFactory = array();

    /**
     * @param IStateFactory $factory
     * @return $this
     */
    public function setStateFactory(IStateFactory $factory)
    {
        $this->_stateFactory = array($factory);
        return $this;
    }

    protected function factoryState($newState)
    {
        foreach ($this->_stateFactory as $factory) {
            if ($factory->isAccept($newState)) {
                return $factory->factory($this->getState(), $newState);
            }
        }

        $factory = new StateFactory($this->getState()->getData());
        return $factory->factory(null, $newState);
    }

    public function dispatch()
    {
        $current = $this->getState();
        $next = $current->execute();

        if (empty($next)) {
            return $current;
        }

        $next = $this->factoryState($next);
        $this->setState($next);
        return $this->dispatch();
    }
}
