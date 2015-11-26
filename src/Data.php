<?php
namespace FormObject;

class Data implements \ArrayAccess, \IteratorAggregate, \Countable, \Serializable
{
    /**
     * @return array assoc
     */
    public function toArray()
    {
        $ref = new \ReflectionObject($this);
        $props = $ref->getProperties(\ReflectionProperty::IS_PUBLIC);
        $static_props = $ref->getStaticProperties();
        if (empty($static_props) === false) {
            $static_props = array_keys($static_props);
        }
        
        $arr = [];
        foreach ($props as $prop) {
            $key = $prop->getName();
            if (in_array($key, $static_props)) continue;

            $arr[$key] = $prop->getValue($this);
        }

        return $arr;
    }

    private static $_keys = [];

    /**
     * @return string[] public property names
     */
    public function getKeys()
    {
        $classname = get_called_class();
        if (array_key_exists($classname, self::$_keys) === false) {
            $arr = $this->toArray();
            self::$_keys[$classname] = array_keys($arr);
        }

        return self::$_keys[$classname];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    public function offsetExists($offset)
    {
        $keys = $this->getKeys();
        if (in_array($offset, $keys)) {
            return isset($this->$offset); 
        }

        return false;
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->$offset;
        }

        throw new \OutOfBoundsException("$offset is no property");
    }

    public function offsetSet($offset , $value )
    {
        if ($this->offsetExists($offset)) {
            $this->$offset = $value;
            return;
        }

        throw new \OutOfBoundsException("$offset is no property");
    }
    
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->$offset = '';
        }
    }

    public function count() { return count($this->getKeys()); }

    public function serialize() { return serialize($this->toArray()); }

    public function unserialize($str)
    {
        $arr = unserialize($str);
        foreach ($arr as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * @param array $data assoc
     * @param array $replace [fromKey => toKey]
     * @return $this
     */
    public function bind(array $data,array $replace=array())
    {
        foreach ($replace as $from => $to) {
            if (array_key_exists($from, $data) === false) {
                throw new \InvalidArgumentException("$from is not found");
            }
            $data[$to] = $data[$from];
            unset($data[$from]);
        }

        $keys = array_intersect($this->getKeys(), array_keys($data));
        foreach ($keys as $key) {
            $this->$key = $data[$key];
        }

        return $this;
    }
}

