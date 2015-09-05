<?php

namespace Doctrine\Manager\Model;

interface ManagerFactoryInterface
{
    /**
     * @param $class
     * @param string $objectManagerName
     * @return ModelManagerInterface
     */
	function getManager($class, $objectManagerName = 'default');
} 