<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 11/17/18
 * Time: 12:28 PM
 */

namespace App\Controller;


use App\Entity\Device;
use App\Form\Device\DeviceEditType;
use App\Service\ProjectionService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/device")
 */
class DeviceController extends BaseController
{
    public function _init()
    {
        $this->addBreadcrumb($this->generateUrl('app_index'), 'Strona główna');
        $this->addBreadcrumb($this->generateUrl('device'), 'Urządzenia');
    }

    /**
     * @Route("", name="device")
     * @Template()
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('d')->from(Device::class, 'd');

        $pagination = $paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1), 20);

        return [
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'pagination'    => $pagination
        ];
    }

    /**
     * @Route("/new", name="device_new", methods="GET|POST")
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        UserPasswordEncoderInterface $passwordEncoder,
        ProjectionService $projectionService
    ): Response
    {
        $this->addBreadcrumb($this->generateUrl('user_new'), 'Rejestruj nowe urządzenie');

        $form = $formFactory->createNamed('', DeviceEditType::class, [
            'name'          => '',
            'trackerUid'    => '',
            'enabled'       => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $device = new Device();

            $data = $form->getData();

            $projectionService->updateModelFromProjection($device, $data);

            $entityManager->persist($device);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Utworzono urządzenie "%s".', $device->getName()));

            return $this->redirectToRoute('device');
        }

        return $this->render('device/edit.html.twig', [
            'device'        => null,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="device_edit", requirements={"id"="^[a-f0-9\-]+$"}, methods="GET|POST")
     * @ParamConverter("device")
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        UserPasswordEncoderInterface $passwordEncoder,
        ProjectionService $projectionService,
        Device $device
    ): Response
    {
        $this->addBreadcrumb($this->generateUrl('device_edit', ['id' => $device->getId()]), sprintf('%s', $device->getName()));

        $form = $formFactory->createNamed('', DeviceEditType::class, $projectionService->getProjection($device));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $projectionService->updateModelFromProjection($device, $data);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Uaktualniono urządzenie "%s".', $device->getName()));

            return $this->redirectToRoute('device');
        }

        return $this->render('device/edit.html.twig', [
            'device'        => $device,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }
}