<?php

namespace Doctrine\Manager\Search;

interface SearchInterface
{
    /**
     * @param int $nb
     *
     * @return SearchInterface
     */
    public function setNb($nb);

    /**
     * @return int
     */
    public function getNb();

    /**
     * @param int $page
     *
     * @return SearchInterface
     */
    public function setPage($page = 1);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param array $sort
     *
     * @return SearchInterface
     */
    public function setSort(array $sort);

    /**
     * @return array
     */
    public function getSort();

    /**
     * @param string $sort
     * @param string $direction
     */
    public function addSort($sort, $direction);

    /**
     * @param string $sort
     */
    public function removeSort($sort);

    /**
     * @param array $criteria
     *
     * @return SearchInterface
     */
    public function setCriteria(array $criteria);

    /**
     * @return array
     */
    public function getCriteria();

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function addCriteria($key, $value);

    /**
     * @param string $key
     */
    public function removeCriteria($key);
}
