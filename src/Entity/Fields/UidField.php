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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait UidField
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     * Assert\Uniq
     * @Groups({"Mercure"})
     * @Projection()
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