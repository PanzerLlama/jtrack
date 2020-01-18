<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/3/20
 * Time: 6:51 PM
 */
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Device;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends BaseController
{
    public function _init()
    {
        $this->addBreadcrumb($this->generateUrl('app_index'), 'Strona główna');
    }

    /**
     * @Route("", name="app_index")
     * @Template()
     */
    public function index()
    {
        return [

            ] + $this->getResponseArray();
    }

    /**
     * @Route("telemetry/{id}", name="device_telemetry", requirements={"id"="^[a-f0-9\-]+$"}, methods="GET")
     * @ParamConverter("device")
     * @Template()
     */
    public function deviceTelemetry(Device $device)
    {

        return [
            'device' => $device
            ] + $this->getResponseArray();
    }
}