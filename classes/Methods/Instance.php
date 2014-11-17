<?php defined('DOCROOT') or die('No direct script access.');

class Methods_Instance
{

    protected static $_instance;

    protected static $_class_instances;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_class_instances = array();
        }

        return self::$_instance;
    }

    /**
     * @param string $classPath
     * @throws Exception
     * @return object
     */
    public function get($classPath, $fullclassname = false)
    {
        if (!$fullclassname) $classPath = 'Methods_' . ucfirst($classPath);
        if (!class_exists($classPath)) throw new Exception("Class {$classPath} not exists!");

        if (array_key_exists($classPath, self::$_class_instances)) {
            return self::$_class_instances[$classPath];
        } else {
            self::$_class_instances[$classPath] = New $classPath();
            return self::$_class_instances[$classPath];
        }
    }

    /**
     * @return $this
     */
    public function removeAll()
    {
        foreach (self::$_class_instances as $classPath => $v) {
            unset(self::$_class_instances[$classPath]);
        }

        return $this;
    }

}