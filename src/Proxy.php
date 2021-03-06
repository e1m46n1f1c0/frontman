<?php

namespace Frontman;

/**
 * Frontman proxy class
 *
 * @package    Frontman
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/hassankhan/frontman/
 * @license    MIT
 * @since      0.1
 */
class Proxy implements ProxyInterface
{
    /**
     * @var array
     */
    protected static $realClasses = array();

    /**
     * Since we don't need a constructor for the proxy class, we just set its
     * visibility to private
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public static function getRealClass()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public static function getConstructorArguments()
    {
        return array();
    }

    /**
     * This method calls the root class with `$arguments` as parameters and
     * returns its result
     *
     * @param  string $method
     * @param  mixed  $arguments
     *
     * @return mixed
     *
     * @throws BadMethodCallException If an undefined method is called
     */
    public static function __callStatic($method, $arguments)
    {
        $realClass = static::getRealClass();
        if (!isset(self::$realClasses[$realClass])) {
            self::$realClasses[$realClass] = new $realClass(static::getConstructorArguments());
        }


        $objectMethod = array(self::$realClasses[$realClass], $method);
        if (is_callable($objectMethod)) {
            return call_user_func_array($objectMethod, $arguments);
        }

        throw new \BadMethodCallException("Method $method does not exist");
    }
}
