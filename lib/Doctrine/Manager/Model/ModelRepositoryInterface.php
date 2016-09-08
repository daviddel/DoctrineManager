<?php

namespace Doctrine\Manager\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Manager\Search\SearchInterface;

interface ModelRepositoryInterface extends ObjectRepository
{
    const OPERATOR_EQ = 'eq';

    const OPERATOR_NEQ = 'neq';

    const OPERATOR_IN = 'in';

    const OPERATOR_NIN = 'nin';

    const OPERATOR_GTE = 'gte';

    const OPERATOR_GT = 'gt';

    const OPERATOR_LTE = 'lte';

    const OPERATOR_LT = 'lt';

    const OPERATOR_LIKE = 'like';

    const OPERATOR_NLIKE = 'nlike';

    public function getQuery(SearchInterface $search);

    public function getQueryBuilder(SearchInterface $search);

    public function buildCriteria(SearchInterface $search, $qb);

    public function buildSort(SearchInterface $search, $qb);
}
