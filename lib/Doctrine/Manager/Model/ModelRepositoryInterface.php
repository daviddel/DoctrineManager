<?php

namespace Doctrine\Manager\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Manager\Search\SearchInterface;

interface ModelRepositoryInterface extends ObjectRepository
{
    public function getQuery(SearchInterface $search);

    public function getQueryBuilder(SearchInterface $search);

    public function buildCriteria(SearchInterface $search, $qb);

    public function buildSort(SearchInterface $search, $qb);
}
