<?php
namespace FormObject\Csrf\Storage;

use FormObject\Csrf\IStorage;

class Callback implements IStorage
{
    private $_save_handler;
    private $_load_handler;

    function __construct(\Closure $save, \Closure $load)
    {
        $this->_save_handler = $save;
        $this->_load_handler = $load;
    }

    /**
     * @param string $str
     */
    public function save($str)
    {
        return call_user_func($this->_save_handler, $str);
    }

    /**
     * @return string
     */
    public function load()
    {
        return call_user_func($this->_load_handler);
    }

    public function clear()
    {
        $this->save(null);
    }
}