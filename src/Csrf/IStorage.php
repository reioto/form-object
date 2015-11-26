<?php
namespace FormObject\Csrf;

interface IStorage
{

    /**
     * @param string $str
     */
    public function save($str);

    /**
     * @return string
     */
    public function load();

    public function clear();
}