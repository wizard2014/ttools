<?php
/**
 * Twitter User class
 *
 * A Read-Only class, created to provide Array Access (maintaining backwards compatibility)
 * while maintaining public access to properties.
 */

namespace TTools;

class User implements \ArrayAccess
{
    protected $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function __get($key)
    {
        if (isset($this->credentials[$key])) {
            return $this->credentials[$key];
        }

        return null;
    }

    public function get($key)
    {
        return $this->$key;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->credentials[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->credentials[$offset]) ? $this->credentials[$offset] : null;
    }

    /**
     * {@inheritdoc}
     * This class is read-only.
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->credentials[$offset] = null;
    }

}