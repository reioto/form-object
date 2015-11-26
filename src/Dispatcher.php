<?php
namespace FormObject;

use FormObject\StateBase as State;

class Dispatcher
{
    private $state;
    function __construct(State $state)
    {
        $this->setState($state);
    }

    /**
     * @param string|State
     */
    protected function setState($state)
    {
        if (is_string($state) && class_exists($state)) {
            $newClass = new \ReflectionClass($state);
            if ($newClass->isSubclassOf(__NAMESPACE__ . '\\StateBase')) {
                $data = $this->getState()->getData();
                $state = $newClass->newInstance($data);
            }else {
                throw new \DomainException("invalid inherits");
            }
        }

        if ($state instanceof State) {
            $this->state = $state;
            return;
        }

        throw new \DomainException();
    }

    public function getState() { return $this->state; }

    public function dispatch()
    {
        $current = $this->getState();
        $next = $current->execute();

        if (empty($next)) {
            return $current;
        }

        $this->setState($next);
        return $this->dispatch();
    }
}
