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
     * Some random cities location, starting data for emulation
     * @var array
     */
    private $cities = [
        [53.13333, 23.16433],   // Białystok
        [50.30582, 18.9742],    // Chorzów
        [54.35205, 18.64637],   // Gdańsk
        [50.25841, 19.02754],   // Katowice
        [50.06143, 19.93658],   // Kraków
        [51.25, 22.56667],      // Lublin
        [50.67211, 17.92533],   // Opole
        [52.40692, 16.92993],   // Poznań
        [52.22977, 21.01178],   // Warszawa
        [51.1, 17.03333]        // Wrocław

    ];

    protected function configure()
    {
        $this
            ->setName('emulator')
            ->setDescription('Generates fake telemetry for devices with emulated trackers.')
            ->addOption('frequency', null, InputOption::VALUE_OPTIONAL, 'Frequency of emulation in seconds (if the emulator runs in screen).');
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
                //$output->writeln(sprintf('Loading devices with emulated trackers.'));

                /** @var Device $device */
                foreach ($query->getQuery()->getResult() as $device) {
                    $this->emulate($device, $output);

                    $device->setStampActivity(new \DateTime());
                }

                $this->entityManager->flush();
                $this->entityManager->clear();

                usleep($sleep);

            } while (true);
        }

        /** @var Device $device */
        foreach ($query->getQuery()->getResult() as $device) {
            $this->emulate($device, $output);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Device $device
     * @param OutputInterface $output
     */
    private function emulate(Device $device, OutputInterface $output) {

        $tracker = $device->getTracker();

        if ($tracker->getEmulatedData('count') === null) {

            //$output->writeln(sprintf('Initiating emulation for device "%s".', $device->getName()));

            $tracker->setEmulatedData('count', 0);

            # randomize the starting location a bit
            $location = $this->cities[rand(0,9)];

            $location[0] += rand(0,50) * 0.001;
            $location[1] += rand(0,50) * 0.001;

            $tracker->setEmulatedData('latitude', $location[0]);
            $tracker->setEmulatedData('longitude', $location[1]);

            # add some start telemetry
            $tracker->setEmulatedData('temperature', [
                'value'     => rand(0, 25),
                'min'       => -20,
                'max'       => 60,
                'modifier'  => 0.1
            ]);
            $tracker->setEmulatedData('humidity', [
                'value'     => rand(10, 95),
                'min'       => 0,
                'max'       => 100,
                'modifier'  => 0.1
            ]);
        }

        $output->writeln(sprintf('Emulating telemetry for device "%s".', $device->getName()));

        /*
        $latitude = $tracker->getEmulatedData('latitude');

        $m = rand(0,1) === 0 ? -0.01 : 0.01;

        $tracker->setEmulatedData('latitude', $latitude + (rand(0,10) * $m));

        $longitude = $tracker->getEmulatedData('longitude');

        $m = rand(0,1) === 0 ? -0.01 : 0.01;

        $tracker->setEmulatedData('longitude', $longitude + (rand(0,10) * $m));
        */

        $p = $tracker->getEmulatedData('temperature');

        # slight chance to change the trend
        if ($p['modifier'] >= 0) {
            $p['modifier'] = rand(0,10) === 0 ? -0.1 : 0.1;
        } else {
            $p['modifier'] = rand(0,10) === 0 ? 0.1 : -0.1;
        }

        $v = (float) $p['value'] + (rand(0,5) * $p['modifier']);

        if ($v >= $p['min'] && $v <= $p['max']) {
            $p['value'] = $v;
        }

        $tracker->setEmulatedData('temperature', $p);

        $p = $tracker->getEmulatedData('humidity');

        if ($p['modifier'] >= 0) {
            $p['modifier'] = rand(0,10) === 0 ? -0.1 : 0.1;
        } else {
            $p['modifier'] = rand(0,10) === 0 ? 0.1 : -0.1;
        }

        $v = (float) $p['value'] + (rand(0,5) * $p['modifier']);

        if ($v >= $p['min'] && $v <= $p['max']) {
            $p['value'] = $v;
        }

        $tracker->setEmulatedData('humidity', $p);

        $t = new SimpleTelemetry($device);

        $t->setLatitude($tracker->getEmulatedData('latitude'));
        $t->setLongitude($tracker->getEmulatedData('longitude'));
        $t->setTemperature($tracker->getEmulatedData('temperature')['value']);
        $t->setHumidity($tracker->getEmulatedData('humidity')['value']);

        $this->entityManager->persist($t);

        $tracker->setEmulatedData('count', ($tracker->getEmulatedData('count') + 1));

    }

}