<?php
namespace FormObject\Dispatcher;

use FormObject\StateBase as State;

interface IStateFactory
{
    /**
     * @param string|\FormObject\StateBase
     * @return boolean
     */
    public function isAccept($state);

    /**
     * @param string|\FormObject\StateBase $nextState
     * @return \FormObject\StateBase
     */
    public function factory($nextState);
}
