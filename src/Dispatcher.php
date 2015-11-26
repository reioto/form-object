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

    private function setState(State $state)
    {
        $this->state = $state;
        $this->dispatch();
    }

    public function getState() { return $this->state; }

    protected function dispatch()
    {
        $current = $this->getState();
        $next = $current->execute();

        if (empty($next)) {
            return $current;
        } elseif (is_string($next) && class_exists($next)) {
            $newClass = new \ReflectionClass($next);
            if ($newClass->isSubclassOf(__NAMESPACE__ . '\\StateBase')) {
                $next = $newClass->newInstance($current->getData());
            }else {
                throw new \DomainException("invalid inherits");
            }
        }

        if ($next instanceof State) {
            $this->setState($next);
            return;
        }

        throw new \DomainException();
    }
}
