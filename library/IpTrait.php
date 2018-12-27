<?php

namespace devtoolboxuk\ipAddress;

trait IpTrait
{
    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }

        foreach (array('get', 'to') as $prefix) {
            $method = $prefix . ucfirst($name);
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }

        throw new \Exception(sprintf('%s is an undefined property', $name));
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;

        }
        throw new \Exception(sprintf('%s is an undefined property', $name));
    }

}
