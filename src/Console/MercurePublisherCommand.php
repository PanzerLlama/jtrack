<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/11/20
 * Time: 1:02 PM
 */
declare(strict_types=1);

namespace App\Console;


use App\Entity\Device;
use App\Entity\SimpleTelemetry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class MercurePublisherCommand extends Command
{
    use LockableTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    protected function configure()
    {
        $this
            ->setName('mercure:publish')
            ->setDescription('Publikuje informacje o urządzeniach do huba mercure.')
            ->addOption('frequency', null, InputOption::VALUE_OPTIONAL, 'Częstotliwość aktualizacji (w sekundach, domyślnie: 30).', 30);
    }

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, PublisherInterface $publisher, string $name = null)
    {
        $this->entityManager    = $entityManager;
        $this->serializer       = $serializer;
        $this->publisher        = $publisher;

        parent::__construct($name);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
         * Native query is much more useful
         *
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('t')
            ->from(SimpleTelemetry::class, 't')
            ->leftJoin('t.device', 'td')
            ->where($queryBuilder->expr()->gte('t.created', ':created'))
            ->orderBy('t.created', 'ASC')
            ->groupBy('t.device_id');

        //echo $query->getQuery()->getSql(); exit;
        */

        $sql = 'SELECT st1.*, d1.name, d1.color, d1.created AS created2, d1.enabled FROM simple_telemetry AS st1 LEFT JOIN device d1 ON d1.id = st1.device_id
                INNER JOIN (
                    SELECT MAX(id) AS id, device_id FROM simple_telemetry GROUP BY device_id
                ) st2 ON st1.id = st2.id';

        $resultSetMapping = new ResultSetMapping();

        $resultSetMapping->addEntityResult(SimpleTelemetry::class, 'st1');
        $resultSetMapping->addFieldResult('st1', 'id', 'id');
        $resultSetMapping->addFieldResult('st1', 'latitude', 'latitude');
        $resultSetMapping->addFieldResult('st1', 'longitude', 'longitude');
        $resultSetMapping->addFieldResult('st1', 'temperature', 'temperature');
        $resultSetMapping->addFieldResult('st1', 'humidity', 'humidity');
        $resultSetMapping->addFieldResult('st1', 'created', 'created');
        $resultSetMapping->addJoinedEntityResult(Device::class, 'd1', 'st1', 'device');
        $resultSetMapping->addFieldResult('d1', 'device_id', 'id');
        $resultSetMapping->addFieldResult('d1', 'name', 'name');
        $resultSetMapping->addFieldResult('d1', 'color', 'color');
        $resultSetMapping->addFieldResult('d1', 'created2', 'created');
        $resultSetMapping->addFieldResult('d1', 'enabled', 'enabled');

        $query = $this->entityManager->createNativeQuery($sql, $resultSetMapping);

        $sleep = (int) ($input->getOption('frequency') * 1000000);

        do {
            /** @var SimpleTelemetry $record */
            foreach ($query->getResult() as $record) {

                $payload = $this->serializer->serialize(
                    $record,
                    'json', [
                        'groups'            => 'Mercure',
                        'datetime_format'   => 'Y/m/d @ H:i:s'
                    ]
                );

                $output->writeln($payload);

                $update = new Update(
                    sprintf('/telemetry/recent'),
                    $payload
                );

                try {
                    $this->publisher->__invoke($update);
                } catch (\Exception $e) {
                    $output->writeln('Publication failed!');
                    throw new \Exception($e->getMessage());
                }
            }

            $this->entityManager->clear();

            usleep($sleep);

        } while (true);
    }
}