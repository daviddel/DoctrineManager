<?php

namespace Doctrine\Manager\Model;

interface ManagerFactoryInterface
{
	function getManager($class, $objectManagerName = 'default');
} 