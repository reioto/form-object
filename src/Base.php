<?php
namespace FormObject;

abstract class Base implements IExecutable
{
    private $_data;
    function __construct(Data $data)
    {
        $this->setData($data);
    }

    /**
     * @return $this
     */
    protected function setData(Data $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * @return Data
     */
    public function getData() { return $this->_data; }

    private $_csrf;
    /**
     * @return Csrf
     */
    public function getCsrf() { return $this->_csrf; }

    /**
     * @param Csrf
     * @return $this
     */
    public function setCsrf(Csrf $obj)
    {
        $this->_csrf = $obj;
        return $this;
    }
}
