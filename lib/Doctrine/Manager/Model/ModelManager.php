<?php

namespace Doctrine\Manager\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelManager implements ModelManagerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @param ContainerInterface $container
     * @param string $class
     * @param ObjectManager $om
     */
    public function __construct($class, ObjectManager $om, ContainerInterface $container = null)
    {
        $this->class = $class;
        $this->om = $om;
        $this->container = $container;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository ?: $this->repository = $this->om->getRepository($this->class);
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
     * @param object $object
     */
    protected function doSave($object)
    {
        $this->om->persist($object);
        $this->om->flush();
    }

    /**
     * @param object $object
     */
    protected function doDelete($object)
    {
        $this->om->remove($object);
        $this->om->flush();
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->getRepository(), $method))
            return call_user_func_array(array($this->getRepository(), $method), $args);
        if (method_exists($this->om, $method))
            return call_user_func_array(array($this->om, $method), $args);
    }
}
