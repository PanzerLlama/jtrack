<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/18/20
 * Time: 6:23 PM
 */
declare(strict_types=1);

namespace App\Console;


use App\Entity\Device;
use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run from crontab or as service
 *
 * Class EmulatorCommand
 * @package App\Console
 */
class DataCommand extends Command
{
    /**
     * @var DataService
     */
    private $dataService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('data')
            ->setDescription('Get device telemetry data as JSON.')
            ->addOption('device', null, InputOption::VALUE_REQUIRED, 'Device id.');
    }

    public function __construct(DataService $dataService, EntityManagerInterface $entityManager, string $name = null)
    {
        $this->dataService      = $dataService;
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
        /** @var Device|null $device */
        $device = $this->entityManager->getRepository(Device::class)->findOneById($input->getOption('device'));

        if (!$device) {
            $output->writeln(sprintf('Device with id "%s" not found.', $input->getOption('device')));
            exit;
        }

        echo json_encode($this->dataService->getChartData($device, 10), JSON_PRETTY_PRINT);

    }

}