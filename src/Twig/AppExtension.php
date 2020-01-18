<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/17/20
 * Time: 21:17 PM
 */
declare(strict_types=1);

namespace App\Twig;


use App\Entity\Device;
use App\Service\DataService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /**
     * @var DataService
     */
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('getChartData', [$this, 'getChartData'])
        ];
    }

    /**
     * @param Device $device
     * @param string $type
     * @param string $interval
     * @param string $start
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getChartData(Device $device, string $type = 'labels', int $interval = 10, string $start = '-8 hours'): array
    {
        static $data;

        if (!isset($data[$device->getId()])) {
            $data[$device->getId()] = $this->dataService->getChartData($device, $interval, $start);
        }

        return isset($data[$device->getId()][$type]) ? $data[$device->getId()][$type] : [];
    }

}