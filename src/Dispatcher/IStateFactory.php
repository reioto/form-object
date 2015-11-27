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
     * @param null|\FormObject\StateBase $currentState
     * @param string|\FormObject\StateBase $newState
     * @return \FormObject\StateBase
     */
    public function factory($currentState, $newState);
}
