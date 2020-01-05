<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/5/20
 * Time: 6:33 PM
 */
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Fields\CreatedField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class SimpleTelemetry
{
    use CreatedField;

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $humidity;

    /**
     * @var Device
     * @ORM\ManyToOne(targetEntity="App\Entity\Device")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
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
     * @return SimpleTelemetry
     */
    public function setId(int $id): SimpleTelemetry
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     * @return SimpleTelemetry
     */
    public function setLatitude(?float $latitude): SimpleTelemetry
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     * @return SimpleTelemetry
     */
    public function setLongitude(?float $longitude): SimpleTelemetry
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    /**
     * @param int|null $humidity
     * @return SimpleTelemetry
     */
    public function setHumidity(?int $humidity): SimpleTelemetry
    {
        $this->humidity = $humidity;
        return $this;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * @param Device $device
     * @return SimpleTelemetry
     */
    public function setDevice(Device $device): SimpleTelemetry
    {
        $this->device = $device;
        return $this;
    }
}