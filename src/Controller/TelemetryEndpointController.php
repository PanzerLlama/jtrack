<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/6/20
 * Time: 5:16 PM
 */
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Device;
use App\Entity\SimpleTelemetry;
use App\Factory\TelemetryCreator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class TelemetryEndpointController extends AbstractController
{
    /**
     * @Route("/telemetry/store/{deviceId}/{controlHash}", name="telemetry_store", requirements={"deviceId"="\d+", "controlHash"="^[a-zA-Z0-9]+$"})
     * @ParamConverter("device", options={ "mapping": {"deviceId":"id"} })
     *
     * #Not so great solution ;)
     * #controlHash is used to "sign" the telemetry sent from the device
     * #simply md5($deviceSecret:$queryString);
     * #not so secure but better than nothing for a start
     */
    public function TelemetryEndpoint(Request $request, TelemetryCreator $creator, Device $device, string $controlHash)
    {
        $queryString = $request->query->get('name_query');

        echo 'queryString: '.$queryString;

        $creator->create(new SimpleTelemetry($device), [
            'latitude' => 10.0988,
            'longitude' => 788.8998,
            'humidity' => 38
        ]);

        exit;

        if (md5(sprintf('%s:%s', $device->getSecret(), $queryString)) !== $controlHash) {
            throw new HttpException(400, 'Invalid signature.');
        }



    }
}