<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/18/20
 * Time: 6:22 PM
 */
declare(strict_types=1);

namespace App\Service;


use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;

class DataService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * @param Device $device
     * @param int $interval
     * @param string $start
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getChartData(Device $device, int $interval = 30, string $start = '-8 hours'): array
    {
        $data = [
            'temperature'   => [],
            'humidity'      => [],
            'labels'        => []
        ];

        $start  = new \DateTime($start);
        $end    = new \DateTime();

        $sql = sprintf('SELECT humidity, temperature, created FROM simple_telemetry AS st1 INNER JOIN (
            SELECT sec_to_time(time_to_sec(created)- time_to_sec(created)%%(%s*60)) as intervals, MAX(id) AS id from simple_telemetry
            WHERE device_id = %s AND created BETWEEN %s AND %s 
            GROUP BY intervals) st2 ON st1.id = st2.id',
            $interval,
            $device->getId(),
            $start->format('YmdHis'),
            $end->format('YmdHis')
        );

        $stmt = $this->entityManager->getConnection()->prepare($sql);

        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {

            $created = new \DateTime($row['created']);

            $data['temperature'][] = $row['temperature'];
            $data['humidity'][] = $row['humidity'];
            $data['labels'][] = $created->format('d/m H:i');
        }

        return $data;
    }
}