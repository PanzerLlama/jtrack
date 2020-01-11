<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 10/26/19
 * Time: 5:39 PM
 */
declare(strict_types=1);

namespace App\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedField
{
    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime")
     * @Groups({"Mercure"})
     */
    private $created;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface|null $created
     * @return CreatedField
     */
    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;
        return $this;
    }
}