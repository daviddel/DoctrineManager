<?php

namespace Doctrine\Manager\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractManager implements ModelManagerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $omName;

    /**
     * @var string
     */
    protected $class;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @param string $class
     * @param string $omName
     * @param ContainerInterface $container
     */
    public function __construct($class, $omName = 'default', ContainerInterface $container = null)
    {
        $this->class = $class;
        $this->omName = $omName;
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getRealClass()
    {
        return \Doctrine\Common\Util\ClassUtils::getRealClass($this->class);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository ?: $this->repository = $this->getObjectManager()->getRepository($this->class);
    }

    /**
     * @return object
     */
    public function create()
    {
        return new $this->class;
    }

    /**
     * @param object $object
     */
    public function save($object)
    {
        $this->doSave($object);
    }

    /**
     * @param object $object
     */
    public function delete($object)
    {
        $this->doDelete($object);
    }

    /**
     * @param mixed $criteria
     * @return object
     */
    public function findOrCreate($criteria)
    {
        if (!is_array($criteria)) {
            $meta = $this->getObjectManager()->getClassMetadata($this->class);
            $identifier = $meta->getSingleIdentifierFieldName();

            $criteria = array($identifier => $criteria);
        }

        $object = $this->getRepository()->findOneBy($criteria);
        if (!is_object($object)) {
            $object = $this->create();
        }

        return $object;
    }

    /**
     * @param object $object
     */
    protected function doSave($object)
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }

    /**
     * @param object $object
     */
    protected function doDelete($object)
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    abstract public function getObjectManager();

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->getRepository(), $method))
            return call_user_func_array(array($this->getRepository(), $method), $args);
        if (method_exists($this->getObjectManager(), $method))
            return call_user_func_array(array($this->getObjectManager(), $method), $args);
    }
}
