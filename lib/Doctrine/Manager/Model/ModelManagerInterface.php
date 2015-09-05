<?php

namespace Doctrine\Manager\Model;

interface ModelManagerInterface
{
    /**
     * @return string
     */
    function getRealClass();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    function getObjectManager();

	/**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
	function getRepository();

	/**
     * @return object
     */
	function create();

	/**
     * @param object $object
     */
	function save($object);

	/**
     * @param object $object
     */
	function delete($object);

    /**
     * @param mixed $criteria
     * @return object
     */
    function findOrCreate($criteria);
} 