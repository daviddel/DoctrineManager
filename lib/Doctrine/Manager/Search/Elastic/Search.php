<?php

namespace Doctrine\Manager\Search\Elastic;

use Doctrine\Manager\Search\Search as BaseSearch;

class Search extends BaseSearch implements SearchInterface
{
    /**
     * @var array
     */
    protected $filterCriteria = array();

    /**
     * @var array
     */
    protected $queryCriteria = array();

    /**
     * @var array
     */
    protected $aggregations = array();

    /**
     * @var array
     */
    protected $aggsCriteria = array();

    /**
     * @var array
     */
    protected $aggsSort = array();

    /**
     * @var array
     */
    protected $fields = array();

    /**
     * @param array
     *
     * @return Search
     */
    public function setFilterCriteria(array $filterCriteria)
    {
        $this->filterCriteria = $filterCriteria;

        return $this;
    }

    /**
     * @return array
     */
    public function getFilterCriteria()
    {
        return $this->filterCriteria;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasFilterCriterion($key)
    {
        return isset($this->filterCriteria[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getFilterCriterion($key)
    {
        return $this->filterCriteria[$key];
    }

    /**
     * @param string
     * @param mixed
     *
     * @return Search
     */
    public function addFilterCriterion($key, $value)
    {
        $this->filterCriteria[$key] = $value;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeFilterCriterion($key)
    {
        unset($this->filterCriteria[$key]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setQueryCriteria(array $queryCriteria)
    {
        $this->queryCriteria = $queryCriteria;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryCriteria()
    {
        return $this->queryCriteria;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasQueryCriterion($key)
    {
        return isset($this->queryCriteria[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getQueryCriterion($key)
    {
        return $this->queryCriteria[$key];
    }

    /**
     * @param string
     * @param mixed
     *
     * @return Search
     */
    public function addQueryCriterion($key, $value)
    {
        $this->queryCriteria[$key] = $value;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeQueryCriterion($key)
    {
        unset($this->queryCriteria[$key]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setAggsCriteria(array $aggsCriteria)
    {
        $this->aggsCriteria = $aggsCriteria;

        return $this;
    }

    /**
     * @return array
     */
    public function getAggsCriteria()
    {
        return $this->aggsCriteria;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasAggsCriterion($key)
    {
        return isset($this->aggsCriteria[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getAggsCriterion($key)
    {
        return $this->aggsCriteria[$key];
    }

    /**
     * @param string
     * @param mixed
     *
     * @return Search
     */
    public function addAggsCriterion($key, $value)
    {
        $this->aggsCriteria[$key] = $value;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeAggsCriterion($key)
    {
        unset($this->aggsCriteria[$key]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setAggsSort(array $aggsSort)
    {
        $this->aggsSort = $aggsSort;

        return $this;
    }

    /**
     * @return array
     */
    public function getAggsSort()
    {
        return $this->aggsSort;
    }

    /**
     * @param string
     * @param string
     *
     * @return Search
     */
    public function addAggsSort($sort, $direction)
    {
        $this->aggsSort[$sort] = $direction;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeAggsSort($sort)
    {
        unset($this->aggsSort[$sort]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeField($field)
    {
        $index = array_search($field, $this->fields);
        unset($this->fields[$index]);

        return $this;
    }

    /**
     * @param array
     *
     * @return Search
     */
    public function setAggregations(array $aggregations)
    {
        $this->aggregations = $aggregations;

        return $this;
    }

    /**
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasAggregation($key)
    {
        return (array_search($key, $this->aggregations) !== false);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getAggregation($key)
    {
        return $this->aggregations[$key];
    }

    /**
     * @param string
     * @param mixed
     *
     * @return Search
     */
    public function addAggregation($key, $value)
    {
        $this->aggregations[$key] = $value;

        return $this;
    }

    /**
     * @param string
     *
     * @return Search
     */
    public function removeAggregation($key)
    {
        unset($this->aggregations[$key]);

        return $this;
    }
}