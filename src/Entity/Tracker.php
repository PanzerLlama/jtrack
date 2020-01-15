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
use App\Entity\Fields\UidField;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}),
 *          @ORM\UniqueConstraint(name="uid_UNIQUE", columns={"uid"})
 *      }
 * )
 */
class Tracker
{
    use CreatedField;
    use EnabledField;
    use UidField;

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Projection()
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
     * @var bool
     * @ORM\Column(type="boolean")
     * @Projection()
     */
    private $flagEmulated;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $emulatorData = [
        'latitude'  => null,
        'longitude' => null,
        'count'     => 0
    ];

    /**
     * @var Device|null
     * @ORM\OneToOne(targetEntity="App\Entity\Device", mappedBy="tracker")
     */
    private $device;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->name, $this->id);
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
     * @return Tracker
     */
    public function setId(int $id): Tracker
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
     * @return Tracker
     */
    public function setName(string $name): Tracker
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
     * @return Tracker
     */
    public function setSecret(string $secret): Tracker
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFlagEmulated(): bool
    {
        return $this->flagEmulated;
    }

    /**
     * @param bool $flagEmulated
     * @return Tracker
     */
    public function setFlagEmulated(bool $flagEmulated): Tracker
    {
        $this->flagEmulated = $flagEmulated;
        return $this;
    }

    /**
     * @return Device|null
     */
    public function getDevice(): ?Device
    {
        return $this->device;
    }

    /**
     * @param Device|null $device
     * @return Tracker
     */
    public function setDevice(?Device $device): Tracker
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @param string $property
     * @return string|null
     */
    public function getEmulatorData(string $property): ?string
    {
        if (isset($this->emulatorData[$property])) {
            return $this->emulatorData[$property];
        }
        return null;
    }

    /**
     * @param string $property
     * @param string $value
     * @return Tracker
     */
    public function setEmulatorData(string $property, string $value): Tracker
    {
        if (!isset($this->emulatorData[$property])) {
            $this->emulatorData[$property] = null;
        }

        $this->emulatorData[$property] = $value;
        return $this;
    }

}