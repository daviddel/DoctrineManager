<?php

namespace Doctrine\Manager\Search;

class Search implements SearchInterface
{
    /**
     * @var int
     */
    protected $nb;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var array
     */
    protected $sort = array();

    /**
     * @var array
     */
    protected $criteria = array();

    /**
     * @param int
     *
     * @return Search
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * @return int
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * @param int
     *
     * @return Search
     */
    public function setPage($page = 1)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string
     * @param string
     *
     * @return Search
     */
    public function addSort($sort, $direction)
    {
        $this->sort[$sort] = $direction;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeSort($sort)
    {
        unset($this->sort[$sort]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param string
     * @param mixed
     *
     * @return Search
     */
    public function addCriteria($key, $value)
    {
        $this->criteria[$key] = $value;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeCriteria($key)
    {
        unset($this->criteria[$key]);

        return $this;
    }
}
