<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/5/20
 * Time: 6:33 PM
 */
declare(strict_types=1);

namespace App\Entity;

use App\Annotations\Projection;
use App\Entity\Fields\CreatedField;
use App\Interfaces\TelemetryInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class SimpleTelemetry implements TelemetryInterface
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
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Assert\Range(
     *      min = -90,
     *      max = 90
     *)
     * @Projection(type="float")
     * @Groups({"Mercure"})
     */
    private $latitude;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
     * @Assert\Range(
     *      min = -180,
     *      max = 180
     * )
     * @Projection(type="float")
     * @Groups({"Mercure"})
     */
    private $longitude;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100
     * )
     * @Projection(type="float")
     * @Groups({"Mercure"})
     */
    private $humidity;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     * @Assert\Range(
     *      min = -50,
     *      max = 100
     * )
     * @Projection(type="float")
     * @Groups({"Mercure"})
     */
    private $temperature;

    /**
     * @var Device
     * @ORM\ManyToOne(targetEntity="App\Entity\Device")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     * @Groups({"Mercure"})
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
        return (float) $this->latitude;
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
        return (float) $this->longitude;
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
     * @return float|null
     */
    public function getHumidity(): ?float
    {
        return (float) $this->humidity;
    }

    /**
     * @param float|null $humidity
     * @return SimpleTelemetry
     */
    public function setHumidity(?float $humidity): SimpleTelemetry
    {
        $this->humidity = $humidity;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTemperature(): ?float
    {
        return (float) $this->temperature;
    }

    /**
     * @param float|null $temperature
     * @return SimpleTelemetry
     */
    public function setTemperature(?float $temperature): SimpleTelemetry
    {
        $this->temperature = $temperature;
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