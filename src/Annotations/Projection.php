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
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $groups = [];

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Projection
     */
    public function setType(?string $type): Projection
    {
        $this->type = $type;
        return $this;
    }

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