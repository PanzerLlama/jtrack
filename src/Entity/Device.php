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
     * @ORM\Column(type="string", length=255)
     * @Projection()
     *
     * Used for signing the telemetry messages
     */
    private $secret;

    /**
     * @var string
     * @ORM\Column(type="string", length=7)
     * @Projection()
     * @Groups({"Mercure"})
     */
    private $color = '#707070';

    /**
     * @var string|null
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Projection()
     */
    private $trackerUid;

    public function __construct()
    {
        $this->created = new \DateTime();
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
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Device
     */
    public function setSecret(string $secret): Device
    {
        $this->secret = $secret;
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
     * @return string|null
     */
    public function getTrackerUid(): ?string
    {
        return $this->trackerUid;
    }

    /**
     * @param string|null $trackerUid
     * @return Device
     */
    public function setTrackerUid(?string $trackerUid): Device
    {
        $this->trackerUid = $trackerUid;
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