<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 8/13/18
 * Time: 11:05 AM
 */

namespace App\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

trait UidField
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $uid;

    /**
     * @param $uid
     *
     * @return UidField
     */
    public function setUid(string $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }
}