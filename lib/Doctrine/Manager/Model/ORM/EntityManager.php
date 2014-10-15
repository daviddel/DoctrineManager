<?php

namespace Doctrine\Manager\Model\ORM;

use Doctrine\Manager\Model\ModelManager;

class EntityManager extends ModelManager
{
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getObjectManager()
	{
		if ($this->om !== null) {
			return $this->om;
		}

		return $this->om = $this->container->get('doctrine')->getManager($this->omName);
	}

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->getObjectManager();
	}
} 