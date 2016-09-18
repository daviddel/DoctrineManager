<?php

namespace Doctrine\Manager\Model\Elastic;

use Doctrine\Manager\Model\Operator;
use Doctrine\Manager\Search\SearchTrait;
use Elastica\Filter\BoolFilter;
use Elastica\Filter\MatchAll;
use Elastica\Filter\Missing;
use Elastica\Filter\Range;
use Elastica\Filter\Term;
use Elastica\Filter\Terms;
use Elastica\Query;
use Elastica\Util;
use FOS\ElasticaBundle\Repository as BaseRepository;
use Doctrine\Manager\Search\Elastic\SearchInterface;

class SearchRepository extends BaseRepository
{
    use SearchTrait;

    /**
     * @param SearchInterface $search
     * @return Query
     */
    public function getQuery(SearchInterface $search)
    {
        return $this->getQueryBuilder($search);
    }

    /**
     * @param SearchInterface $search
     * @return Query
     */
    public function getQueryBuilder(SearchInterface $search)
    {
        $q = new Query();

        $boolFilter = new BoolFilter();
        if ($this->buildSearchFilterCriteria($search, $boolFilter) == 0) {
            $boolFilter = new MatchAll();
        }

        $boolQuery = new Query\BoolQuery();
        if ($this->buildSearchQueryCriteria($search, $boolQuery) == 0) {
            $boolQuery = new Query\MatchAll();
        }

        $filtered = new Query\Filtered($boolQuery, $boolFilter);
        $q->setQuery($filtered);

        if ($search->getSort()) {
            $this->buildSearchSort($search, $q);
        }

        if ($search->getNb() !== null) {
            $q->setSize($search->getNb());
        }
        if ($search->getPage() !== null) {
            $q->setFrom(($search->getPage() - 1) * $search->getNb());
        }

        $fields = $search->getFields();
        if (!empty($fields)) {
            $q->setFields($fields);
        }

        return $q;
    }

    /**
     * @param SearchInterface $search
     * @param BoolFilter      $bool
     *
     * @return int
     */
    public function buildSearchFilterCriteria(SearchInterface $search, BoolFilter $bool)
    {
        $nbFilters = 0;
        if ($bool instanceof BoolFilter) {
            foreach ($search->getFilterCriteria() as $key => $values) {
                if (!is_array($values)) {
                    $this->buildSearchFilterCriterion($bool, $key, $values);
                    $nbFilters++;
                } else {
                    foreach ($values as $value) {
                        $this->buildSearchFilterCriterion($bool, $key, $value);
                        $nbFilters++;
                    }
                }
            }
        }

        return $nbFilters;
    }

    protected function buildSearchFilterCriterion(BoolFilter $bool, $key, $value)
    {
        if (!is_array($value)) {
            if ($value === null) {
                $bool->addMust(new Missing($key));
            } else {
                $bool->addMust(new Term(array($key => $value)));
            }
        } else {
            $operator = key($value);
            $value = current($value);

            if (is_array($value) && $this->isAssociatedArray($value)) {
                if (isset($value['date'])) {
                    if (isset($value['timezone'])) {
                        $timezone = new \DateTimeZone($value['timezone']);
                        $value = new \DateTime($value['date'], $timezone);
                    } else {
                        $value = new \DateTime($value['date']);
                    }

                    $value = Util::convertDateTimeObject($value);
                }
            }

            switch ($operator) {
                case Operator::OPERATOR_EQ:
                    if ($value === null) {
                        $bool->addMust(new Missing($key));
                    } else {
                        $bool->addMust(new Term(array($key => $value)));
                    }
                    break;
                case Operator::OPERATOR_NEQ:
                    if ($value === null) {
                        $bool->addMustNot(new Missing($key));
                    } else {
                        $bool->addMustNot(new Term(array($key => $value)));
                    }
                    break;
                case Operator::OPERATOR_IN:
                    $bool->addMust(new Terms($key, $value));
                    break;
                case Operator::OPERATOR_NIN:
                    $bool->addMustNot(new Terms($key, $value));
                    break;
                case Operator::OPERATOR_GT:
                    $bool->addMust(new Range($key, array('gt' => $value)));
                    break;
                case Operator::OPERATOR_GTE:
                    $bool->addMust(new Range($key, array('gte' => $value)));
                    break;
                case Operator::OPERATOR_LT:
                    $bool->addMust(new Range($key, array('lt' => $value)));
                    break;
                case Operator::OPERATOR_LTE:
                    $bool->addMust(new Range($key, array('lte' => $value)));
                    break;
            }
        }
    }

    /**
     * @param SearchInterface $search
     * @param Query\BoolQuery      $bool
     *
     * @return int
     */
    public function buildSearchQueryCriteria(SearchInterface $search, Query\BoolQuery $bool)
    {
        $nbQueries = 0;
        if ($bool instanceof Query\BoolQuery) {
            foreach ($search->getQueryCriteria() as $key => $values) {
                if (!is_array($values)) {
                    $this->buildSearchQueryCriterion($bool, $key, $values);
                    $nbQueries++;
                } else {
                    foreach ($values as $value) {
                        $this->buildSearchQueryCriterion($bool, $key, $value);
                        $nbQueries++;
                    }
                }
            }
        }

        return $nbQueries;
    }

    protected function buildSearchQueryCriterion(Query\BoolQuery $bool, $key, $value)
    {
        if (!is_array($value)) {
            $bool->addMust(new Query\Term(array($key => $value)));
        } else {
            $operator = key($value);
            $value = current($value);

            if (is_array($value) && $this->isAssociatedArray($value)) {
                if (isset($value['date'])) {
                    if (isset($value['timezone'])) {
                        $timezone = new \DateTimeZone($value['timezone']);
                        $value = new \DateTime($value['date'], $timezone);
                    } else {
                        $value = new \DateTime($value['date']);
                    }

                    $value = Util::convertDateTimeObject($value);
                }
            }

            switch ($operator) {
                case Operator::OPERATOR_EQ:
                    $bool->addMust(new Query\Term(array($key => $value)));
                    break;
                case Operator::OPERATOR_NEQ:
                    $bool->addMustNot(new Query\Term(array($key => $value)));
                    break;
                case Operator::OPERATOR_IN:
                    $bool->addMust(new Query\Terms($key, $value));
                    break;
                case Operator::OPERATOR_NIN:
                    $bool->addMustNot(new Query\Terms($key, $value));
                    break;
                case Operator::OPERATOR_GT:
                    $bool->addMust(new Query\Range($key, array('gt' => $value)));
                    break;
                case Operator::OPERATOR_GTE:
                    $bool->addMust(new Query\Range($key, array('gte' => $value)));
                    break;
                case Operator::OPERATOR_LT:
                    $bool->addMust(new Query\Range($key, array('lt' => $value)));
                    break;
                case Operator::OPERATOR_LTE:
                    $bool->addMust(new Query\Range($key, array('lte' => $value)));
                    break;
            }
        }
    }

    /**
     * @param SearchInterface $search
     * @param BoolFilter      $bool
     *
     * @return int
     */
    public function buildSearchAggsCriteria(SearchInterface $search, BoolFilter $bool)
    {
        $nbFilters = 0;
        if ($bool instanceof BoolFilter) {
            foreach ($search->getAggsCriteria() as $key => $values) {
                if (!is_array($values)) {
                    $this->buildSearchFilterCriterion($bool, $key, $values);
                    $nbFilters++;
                } else {
                    foreach ($values as $value) {
                        $this->buildSearchFilterCriterion($bool, $key, $value);
                        $nbFilters++;
                    }
                }
            }
        }

        return $nbFilters;
    }

    /**
     * @param SearchInterface $search
     * @param Query           $q
     */
    public function buildSearchSort(SearchInterface $search, Query $q)
    {
        foreach ($search->getSort() as $sortField => $sortDirection) {
            if (!is_array($sortDirection)) {
                $sortDirection = array('order' => $sortDirection);
            }
            $q->addSort(array($sortField => $sortDirection));
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isAssociatedArray(array $array)
    {
        if (array() === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
