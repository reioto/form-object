<?php
namespace FormObject;

abstract class StateBase extends Base
{
    protected $name = '';

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name === '') {
            return get_class($this);
        }

        return $this->name;
    }
}
