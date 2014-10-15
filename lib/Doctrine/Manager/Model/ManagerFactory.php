<?php

namespace Doctrine\Manager\Model;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ManagerFactory implements ManagerFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $managers = array();

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     * @param string $class
     */
    public function getManager($class, $objectManagerName = 'default')
    {
        if (isset($this->managers[$class])) {
            return $this->managers[$class];
        }

        $reader = new AnnotationReader();
        /** @var \Doctrine\Manager\Mapping\Annotation\ModelManager $annotation */
        $annotation = $reader->getClassAnnotation(
            new \ReflectionClass($class),
            'Doctrine\\Manager\\Mapping\\Annotation\\ModelManager');

        if (!$annotation || !($managerClass = $annotation->class)) {
            throw new \Exception(sprintf('Manager is not defined for class "%s"', basename($class)));
        }
        $manager = new $managerClass($class, $objectManagerName, $this->container);

        $this->managers[$class] = $manager;

        return $manager;
    }
}
