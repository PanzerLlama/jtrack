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
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Projection()
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
}