<?php

namespace Doctrine\Manager\Search;

trait SearchTrait
{
    public function buildSearch(array $criteria = array(), array $reverseCriteria = array(), array $sort = null, $page = null, $nb = null, SearchInterface $search = null)
    {
        if (!$search) {
            $search = new Search();
        }

        $search->setCriteria($criteria);
        $search->setReverseCriteria($reverseCriteria);

        if ($sort) {
            $search->setSort($sort);
        }
        if ($page) {
            $search->setPage($page);
        }
        if ($nb) {
            $search->setNb($nb);
        }

        return $search;
    }
}
