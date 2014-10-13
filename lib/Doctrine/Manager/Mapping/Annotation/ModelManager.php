<?php

namespace Doctrine\Manager\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class ModelManager extends Annotation
{
    /**
     * @var string
     */
    public $class;
}