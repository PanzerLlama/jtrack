<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/15/20
 * Time: 5:41 PM
 */
declare(strict_types=1);

namespace App\Console;


use App\Entity\Device;
use App\Entity\SimpleTelemetry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mercure\Update;

/**
 * Run from crontab or as service
 *
 * Class EmulatorCommand
 * @package App\Console
 */
class EmulatorCommand extends Command
{
    use LockableTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $cities = [
        'Białystok' => [53.13333, 23.16433],
        'Chorzów'   => [50.30582, 18.9742],
        'Gdańsk'    => [54.35205, 18.64637],
        'Katowice'  => [50.25841, 19.02754],
        'Kraków'    => [50.06143, 19.93658],
        'Lublin'    => [51.25, 22.56667],
        'Opole'     => [50.67211, 17.92533],
        'Poznań'    => [52.40692, 16.92993],
        'Warszawa'  => [52.22977, 21.01178],
        'Wrocław'   => [51.1, 17.03333]

    ];

    protected function configure()
    {
        $this
            ->setName('emulator')
            ->setDescription('Generuje dane dla urządzeń z emulowanymi trackerami.')
            ->addOption('frequency', null, InputOption::VALUE_OPTIONAL, 'Częstotliwość emulacji (w sekundach, domyślnie: 30).', 30);
    }

    /**
     * EmulatorCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(EntityManagerInterface $entityManager, string $name = null)
    {
        $this->entityManager    = $entityManager;

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
            ->select('d')
            ->from(Device::class, 'd')
            ->leftJoin('d.tracker', 'dt')
            ->where($queryBuilder->expr()->eq('dt.flagEmulated', 1))
            ->orderBy('d.created', 'ASC');

        if ($input->getOption('frequency')) {
            $sleep = (int)($input->getOption('frequency') * 1000000);
        }

        if (isset($sleep)) {

            do {
                $output->writeln(sprintf('Fetching emulated devices.'));

                /** @var Device $device */
                foreach ($query->getQuery()->getResult() as $device) {
                    $this->emulate($device);
                }

                $this->entityManager->flush();
                $this->entityManager->clear();

                usleep($sleep);

            } while (true);
        }

        /** @var Device $device */
        foreach ($query->getQuery()->getResult() as $device) {
            $this->emulate($device);
        }

        $this->entityManager->flush();
    }

    private function emulate(Device $device) {

    }

}