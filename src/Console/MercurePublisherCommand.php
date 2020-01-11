<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/11/20
 * Time: 1:02 PM
 */
declare(strict_types=1);

namespace App\Console;


use App\Entity\SimpleTelemetry;
use Doctrine\ORM\EntityManagerInterface;
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
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('t')
            ->from(SimpleTelemetry::class, 't')
            ->leftJoin('t.device', 'td')
            ->where($queryBuilder->expr()->gte('t.created', ':created'))
            ->orderBy('t.created', 'ASC');

        $sleep = (int) ($input->getOption('frequency') * 1000000);

        do {

            $stamp = new \DateTime();

            $stamp->modify(sprintf('-%s minutes', $input->getOption('frequency')));

            $query->setParameter('created', $stamp);

            $output->writeln(sprintf('Fetching records - %s.', $stamp->format('Y-m-d@H:i:s')));

            /** @var SimpleTelemetry $record */
            foreach ($query->getQuery()->getResult() as $record) {

                $payload = $this->serializer->serialize(
                    $record,
                    'json', ['groups' => 'Mercure']
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