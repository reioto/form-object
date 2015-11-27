<?php
namespace FormObject;

interface IDispatcher
{
    /**
     * @return \FormObject\StateBase
     */
    public function dispatch();
}
