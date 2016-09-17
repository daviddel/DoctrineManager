<?php

namespace Doctrine\Manager\Model;

class Operator
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

    /**
     * @return array
     */
    static public function getOperators()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}