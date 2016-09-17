<?php

namespace Doctrine\Manager\Search\Elastic;

use Doctrine\Manager\Search\SearchInterface as BaseSearchInterface;

interface SearchInterface extends BaseSearchInterface
{
    /**
     * @return array
     */
    function getFilterCriteria();

    /**
     * @return array
     */
    function getQueryCriteria();

    /**
     * @return array
     */
    public function getAggregations();

    /**
     * @param array $aggregations
     * @return Search
     */
    public function setAggregations(array $aggregations);

    /**
     * @return array
     */
    function getAggsCriteria();

    /**
     * @return array
     */
    function getAggsSort();

    /**
     * @return array
     */
    function getFields();
}