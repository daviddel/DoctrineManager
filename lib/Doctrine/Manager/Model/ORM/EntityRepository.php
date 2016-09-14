<?php

namespace Doctrine\Manager\Model\ORM;

use Doctrine\Manager\Model\ModelRepositoryInterface;
use Doctrine\Manager\Search\SearchInterface;
use Doctrine\Manager\Search\SearchTrait;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\QueryBuilder;

class EntityRepository extends BaseEntityRepository implements ModelRepositoryInterface
{
    use SearchTrait;

    protected $alias;

    public function getQuery(SearchInterface $search)
    {
        return $this->getQueryBuilder($search)->getQuery();
    }

    public function getQueryBuilder(SearchInterface $search)
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        $this->buildCriteria($search, $qb);
        $this->buildSort($search, $qb);

        if ($search->getNb()) {
            $qb->setMaxResults($search->getNb());
        }
        if ($search->getPage()) {
            $qb->setFirstResult(($search->getPage() - 1) * $search->getNb());
        }

        return $qb;
    }

    public function buildCriteria(SearchInterface $search, $qb)
    {
        if (!$qb instanceof QueryBuilder) {
            return null;
        }

        foreach ($search->getCriteria() as $key => $value) {
            $this->buildCriterion($qb, $key, $value);
        }
    }

    protected function buildCriterion($qb, $key, $value)
    {
        if (!$qb instanceof QueryBuilder) {
            return null;
        }

        if (!(strpos($key, '.') !== false)) {
            $key = $this->alias . '.' . $key;
        }
        $param = ':' . str_replace('.', '_', $key);

        if (!is_array($value)) {
            $criterion = $qb->expr()->eq($key, $param);
            $qb->andWhere($criterion)->setParameter($param, $value);
        } else {
            foreach ($value as $operator => $cValue) {
                switch ($operator) {
                    case ModelRepositoryInterface::OPERATOR_EQ:
                        if ($cValue !== null) {
                            $criterion = $qb->expr()->eq($key, $param);
                        } else {
                            $criterion = $qb->expr()->isNull($key);
                        }
                        break;
                    case ModelRepositoryInterface::OPERATOR_NEQ:
                        if ($cValue !== null) {
                            $criterion = $qb->expr()->neq($key, $param);
                        } else {
                            $criterion = $qb->expr()->isNotNull($key);
                        }
                        break;
                    case ModelRepositoryInterface::OPERATOR_IN:
                        $criterion = $qb->expr()->in($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_NIN:
                        $criterion = $qb->expr()->notIn($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_LIKE:
                        $criterion = $qb->expr()->like($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_NLIKE:
                        $criterion = $qb->expr()->notLike($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_GT:
                        $criterion = $qb->expr()->gt($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_GTE:
                        $criterion = $qb->expr()->gte($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_LT:
                        $criterion = $qb->expr()->lt($key, $param);
                        break;
                    case ModelRepositoryInterface::OPERATOR_LTE:
                        $criterion = $qb->expr()->lte($key, $param);
                        break;
                }

                if (isset($criterion)) {
                    $qb->andWhere($criterion)->setParameter($param, $cValue);
                }
            }
        }
    }

    public function buildSort(SearchInterface $search, $qb)
    {
        if (!$qb instanceof QueryBuilder) {
            return null;
        }

        foreach ($search->getSort() as $sortField => $sortDirection) {
            $qb->addOrderBy($this->alias.'.'.$sortField, $sortDirection);
        }
    }

    protected function getShortClassName()
    {
        $metadata = $this->getClassMetadata();
        $refl = $metadata->getReflectionClass();

        return $refl->getShortName();
    }

    protected function getAlias()
    {
        return $this->alias ?: $this->alias = strtolower(substr(basename($this->getShortClassName()), 0, 1));
    }
}
