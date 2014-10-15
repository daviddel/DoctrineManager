<?php

namespace Doctrine\Manager\Model\ORM;

use Doctrine\Manager\Model\AbstractManager;

class EntityManager extends AbstractManager
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