<?php
namespace FormObject\Csrf;

class Hash implements \Serializable
{
    private $_hash;

    function __construct($hash)
    {
        if (is_string($hash) === false) {
            throw new \InvalidArgumentException('it is not strings');
        }
        $this->_hash = $hash;
    }

    public function getValue() { return $this->_hash; }

    public function __toString() { return $this->getValue(); }

    public function __clone() { return new self($this->getValue()); }

    public function serialize() { return $this->getValue(); }

    public function unserialize($str) { $this->_hash = $str; }

    /**
     * @param int $str_length
     * @return self
     */
    public static function make($str_length=48)
    {
        if ($str_length === 0) return '';

        $bincode = file_get_contents(
            '/dev/urandom', false, null, 0, ceil($str_length / 2)
        );
        $hash = bin2hex($bincode);
        if (strlen($hash) > $str_length) {
            return substr($hash, 0, $str_length);
        }

        return new self($hash);
    }
}