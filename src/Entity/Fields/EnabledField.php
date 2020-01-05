<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 8/13/18
 * Time: 11:05 AM
 */

namespace App\Entity\Fields;

use App\Annotations\Projection;
use Doctrine\ORM\Mapping as ORM;

trait EnabledField
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Projection()
     */
    private $enabled = false;

    /**
     * @param bool $enabled
     *
     * @return EnabledField
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return $this
     */
    public function enable(): self
    {
        $this->enabled = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function disable(): self
    {
        $this->enabled = false;
        return $this;
    }
}