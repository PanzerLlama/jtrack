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
use App\Exception\InvalidTelemetryException;
use App\Factory\TelemetryCreator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class TelemetryEndpointController extends AbstractController
{
    /**
     * Not so great solution ;)
     * controlHash is used to "sign" the telemetry sent from the device
     * simply md5($deviceSecret:$queryString);
     * not so secure but better than nothing for a start
     *
     * @Route("/telemetry/store/{deviceId}/{controlHash}", name="telemetry_store", requirements={"deviceId"="\d+", "controlHash"="^[a-zA-Z0-9]+$"})
     * @ParamConverter("device", options={ "mapping": {"deviceId":"id"} })
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TelemetryCreator $creator
     * @param Device $device
     * @param string $controlHash
     * @return Response
     */
    public function TelemetryEndpoint(Request $request, EntityManagerInterface $entityManager, TelemetryCreator $creator, Device $device, string $controlHash)
    {
        if (md5(sprintf('%s:%s', $device->getSecret(), $request->getQueryString())) !== $controlHash) {
            echo md5(sprintf('%s:%s', $device->getSecret(), $request->getQueryString()));
            throw new HttpException(400, 'Invalid signature.');
        }

        try {
            $telemetry = $creator->create(new SimpleTelemetry($device), $request);
        } catch (InvalidTelemetryException $e) {
            return new Response('ERROR', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $entityManager->persist($telemetry);
        $entityManager->flush();

        return new Response('OK', Response::HTTP_OK);
    }
}