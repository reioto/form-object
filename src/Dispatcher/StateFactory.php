<?php
namespace FormObject\Dispatcher;

use FormObject\StateBase as State;
use FormObject\Data;

class StateFactory implements IStateFactory
{
    private $_default = null;

    function __construct(Data $default)
    {
        $this->setDefaultData($default);
    }

    /**
     * @param Data
     * @return $this
     */
    protected function setDefaultData(Data $data)
    {
        $this->_default = $data;
        return $this;
    }

    /**
     * @return Data
     */
    public function getDefaultData() { return $this->_default; }

    public function isAccept($state)
    {
        if (is_string($state) && class_exists($state)) {
            $checkClass = 'FormObject\\StateBase';
            $newClass = new \ReflectionClass($state);
            if ($newClass->isSubClassOf($checkClass)) {
                return true;
            }
        } elseif ($state instanceof State) {
            return true;
        }

        return false;
    }

    /**
     * @param null|State $currentState
     * @param string|State $newState
     * @return State
     */
    public function factory($currentState, $newState)
    {
        if ($newState instanceof State) {
            return $newState;
        } elseif ($this->isAccept($newState) === false) {
            throw new \InvalidArgumentException('Not Accepted State');
        } elseif (is_string($newState)) {
            $newClass = new \ReflectionClass($newState);
            if ($currentState === null) {
                $data = $this->getDefaultData();
            }else {
                $data = $currentState->getData();
            }
            return $newClass->newInstance($data);
        }

        throw new \InvalidArgumentException('UnKnown Type');
    }
}
