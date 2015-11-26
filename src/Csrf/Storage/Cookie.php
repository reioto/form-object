<?php
namespace FormObject\Csrf\Storage;

use FormObject\Csrf\IStorage;

class Cookie implements IStorage
{
    public $cookie_name = 'formsess';
    public $cookie_path = '/';
    public $cookie_domain = '';
    public $secure = false;
    public $expire_sec = 7200;

    /**
     * @return array assoc
     */
    protected function getOptions()
    {
        return array(
            'name' => (string) $this->cookie_name,
            'expire' => (int) $this->expire_sec,
            'path' => (string) $this->cookie_path,
            'domain' => (string) $this->cookie_domain,
            'secure' => (boolean) $this->secure
        );        
    }

    /**
     * @param string $str
     */
    public function save($str)
    {
        $option = $this->getOptions();
        $key = $option['name'];
        $path = $option['path'];
        $domain = $option['domain'];
        $secure = $option['secure'];
        $expire = $option['expire'];
        $expire = ($expire > 0) ? time() + $expire : 0;
        setcookie($key, (string) $str, $expire, $path, $domain, $secure);
    }

    /**
     * @return string
     */
    public function load()
    {
        $key = $this->cookie_name;
        if (array_key_exists($key, $_COOKIE)) {
            return $_COOKIE[$key];
        }

        return '';
    }

    public function clear()
    {
        $option = $this->getOptions();
        $key = $option['name'];
        $path = $option['path'];
        $domain = $option['domain'];
        $secure = $option['secure'];
        $expire = time() - 86500;
        setcookie($key, '', $expire, $path, $domain, $secure);
    }
}