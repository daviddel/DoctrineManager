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
        $this->buildReverseCriteria($search, $qb);
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
        if ($qb instanceof QueryBuilder) {
            foreach ($search->getCriteria() as $key => $value) {
                if (!(strpos($key, '.') !== false)) {
                    $key = $this->alias.'.'.$key;
                }
                $param = str_replace('.', '_', $key);

                if ($value === null) {
                    $qb->andWhere($qb->expr()->isNull($key));
                } elseif (is_array($value)) {
                    $qb->andWhere($qb->expr()->in($key, ':'.$param));
                } else {
                    $qb->andWhere($qb->expr()->eq($key, ':'.$param));
                }

                if (is_object($value)) {
                    $qb->setParameter($param, $value->getId());
                } else {
                    $qb->setParameter($param, $value);
                }
            }
        }
    }

    public function buildReverseCriteria(SearchInterface $search, $qb)
    {
        if ($qb instanceof QueryBuilder) {
            foreach ($search->getReverseCriteria() as $key => $value) {
                if (!(strpos($key, '.') !== false)) {
                    $key = $this->alias.'.'.$key;
                }
                $param = str_replace('.', '_', $key);

                if ($value === null) {
                    $qb->andWhere($qb->expr()->isNotNull($key));
                } elseif (is_array($value)) {
                    $qb->andWhere($qb->expr()->notIn($key, ':'.$param));
                } else {
                    $qb->andWhere($qb->expr()->neq($key, ':'.$param));
                }

                if (is_object($value)) {
                    $qb->setParameter($param, $value->getId());
                } else {
                    $qb->setParameter($param, $value);
                }
            }
        }
    }

    public function buildSort(SearchInterface $search, $qb)
    {
        if ($qb instanceof QueryBuilder) {
            foreach ($search->getSort() as $sortField => $sortDirection) {
                $qb->addOrderBy($this->alias.'.'.$sortField, $sortDirection);
            }
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
