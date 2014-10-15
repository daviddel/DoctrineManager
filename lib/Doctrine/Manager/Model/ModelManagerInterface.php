<?php

namespace Doctrine\Manager\Model;

interface ModelManagerInterface
{
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
} 