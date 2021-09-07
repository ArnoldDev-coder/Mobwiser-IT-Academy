<?php

namespace Kernel\Session;

final class Session implements \ArrayAccess
{

    /**
     * assure que la session est active
     */
    private function ensureThatSessionStart() : void
    {
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }
    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public function get(string $key, mixed $defaultValue = null): mixed
    {
        $this->ensureThatSessionStart();
        if (array_key_exists($key, $_SESSION)){
            return $_SESSION[$key];
        }return $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed|null $value
     */
    public function set(string $key, mixed $value = null):void
    {
        $this->ensureThatSessionStart();
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->ensureThatSessionStart();
        unset($_SESSION[$key]);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        $this->ensureThatSessionStart();
        return array_key_exists($offset, $_SESSION);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }
}