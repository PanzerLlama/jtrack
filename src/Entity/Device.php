<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/5/20
 * Time: 6:07 PM
 */
declare(strict_types=1);

namespace App\Entity;


use App\Annotations\Projection;
use App\Entity\Fields\CreatedField;
use App\Entity\Fields\EnabledField;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 */
class Device
{
    use CreatedField;
    use EnabledField;

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"Mercure"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Projection()
     * @Groups({"Mercure"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=7)
     * @Projection()
     * @Groups({"Mercure"})
     */
    private $color = '#707070';

    /**
     * @var Tracker|null
     * @ORM\OneToOne(targetEntity="App\Entity\Tracker", inversedBy="device")
     * @Projection()
     */
    private $tracker;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stampActivity;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->getName(), $this->getId());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Device
     */
    public function setId(int $id): Device
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Device
     */
    public function setName(string $name): Device
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Device
     */
    public function setColor(string $color): Device
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Tracker|null
     */
    public function getTracker(): ?Tracker
    {
        return $this->tracker;
    }

    /**
     * @param Tracker|null $tracker
     * @return Device
     */
    public function setTracker(?Tracker $tracker): Device
    {
        if ($this->tracker) {
            $this->tracker->setDevice(null);
        }

        $this->tracker = $tracker;

        if ($tracker) {
            $tracker->setDevice($tracker ? $this : null);
        }
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStampActivity(): ?\DateTime
    {
        return $this->stampActivity;
    }

    /**
     * @param \DateTime|null $stampActivity
     * @return Device
     */
    public function setStampActivity(?\DateTime $stampActivity): Device
    {
        $this->stampActivity = $stampActivity;
        return $this;
    }

    /**
     * @return array|null
     * @Groups({"Mercure"})
     */
    public function getDecimalColor(): ?array
    {
        return [hexdec(substr($this->color,1,2)), hexdec(substr($this->color,3,2)), hexdec(substr($this->color,5,2))];
    }
}