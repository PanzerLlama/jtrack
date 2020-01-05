<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 3:08 PM
 */
declare(strict_types=1);

namespace App\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Projection
{
    /**
     * @var array
     */
    public $groups = [];

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     * @return Projection
     */
    public function setGroups(array $groups): Projection
    {
        $this->groups = $groups;
        return $this;
    }
}