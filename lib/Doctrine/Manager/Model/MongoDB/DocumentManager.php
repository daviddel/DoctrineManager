<?php

namespace Doctrine\Manager\Model\MongoDB;

use Doctrine\Manager\Model\ModelManager;

class DocumentManager extends ModelManager
{
	/**
	 * @return \Doctrine\ODM\MongoDB\DocumentManager
	 */
	public function getObjectManager()
	{
		if ($this->om !== null) {
			return $this->om;
		}

		return $this->om = $this->container->get('doctrine_mongodb')->getManager($this->omName);
	}

	/**
	 * @return \Doctrine\ODM\MongoDB\DocumentManager
	 */
	public function getDocumentManager()
	{
		return $this->getObjectManager();
	}
} 