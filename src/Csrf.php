<?php
namespace FormObject;

use FormObject\Csrf\IStorage;
use FormObject\Csrf\Hash;

class Csrf
{
    private $_storage;
    function __construct(IStorage $storage)
    {
        $this->_storage = $storage;
    }

    public function getStorage() { return $this->_storage; }

    /**
     * @return boolean
     */
    public function hasHash()
    {
        $hash = $this->getStorage()->load();
        if ($hash === null) {
            return false;
        }else if ( $hash instanceof Hash) {
            $hash = $hash->getValue();
        }

        return empty($hash) === false;
    }

    /**
     * @param string $str
     * @return boolean
     */
    public function verify($str)
    {
        $saved = $this->getStorage()->load();
        if ( $saved instanceof Hash) {
            $saved = $saved->getValue();
        }
        return $saved === $str;
    }

    /**
     * @param string|Hash
     */
    public function save($hash)
    {
        return $this->getStorage()->save($hash);
    }

    public function destroy()
    {
        return $this->getStorage()->clear();
    }
}