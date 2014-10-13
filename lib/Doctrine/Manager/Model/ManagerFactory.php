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

        /** @todo $objectManager */
        $objectManager = $this->container->get('doctrine')->getManager($objectManagerName);

        if (!$annotation || !($managerClass = $annotation->class)) {
            $managerClass = 'Doctrine\\Manager\\Model\\ModelManager';
        }
        $manager = new $managerClass($class, $objectManager, $this->container);

        $this->managers[$class] = $manager;

        return $manager;
    }
}
