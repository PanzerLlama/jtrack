<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 11/17/18
 * Time: 12:28 PM
 */

namespace App\Controller;


use App\Entity\Device;
use App\Entity\Tracker;
use App\Form\Device\DeviceEditType;
use App\Form\Tracker\TrackerEditType;
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
 * @Route("/tracker")
 */
class TrackerController extends BaseController
{
    public function _init()
    {
        $this->addBreadcrumb($this->generateUrl('app_index'), 'Strona główna');
        $this->addBreadcrumb($this->generateUrl('tracker'), 'Trackery');
    }

    /**
     * @Route("", name="tracker")
     * @Template()
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('t')->from(Tracker::class, 't');

        $pagination = $paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1), 20);

        return [
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'pagination'    => $pagination
        ];
    }

    /**
     * @Route("/new", name="tracker_new", methods="GET|POST")
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        ProjectionService $projectionService
    ): Response
    {
        $this->addBreadcrumb($this->generateUrl('user_new'), 'Rejestruj nowy tracker');

        $form = $formFactory->createNamed('', TrackerEditType::class, [
            'name'          => '',
            'uid'           => '',
            'secret'        => '',
            'flagEmulated'  => false,
            'enabled'       => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entity = new Tracker();

            $data = $form->getData();

            $projectionService->updateModelFromProjection($entity, $data);

            $entityManager->persist($entity);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Utworzono tracker "%s".', $entity->getName()));

            return $this->redirectToRoute('tracker');
        }

        return $this->render('tracker/edit.html.twig', [
            'entity'        => null,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tracker_edit", requirements={"id"="^[a-f0-9\-]+$"}, methods="GET|POST")
     * @ParamConverter("tracker")
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        ProjectionService $projectionService,
        Tracker $entity
    ): Response
    {
        $this->addBreadcrumb($this->generateUrl('tracker_edit', ['id' => $entity->getId()]), sprintf('%s', $entity->getName()));

        $form = $formFactory->createNamed('', TrackerEditType::class, $projectionService->getProjection($entity));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $projectionService->updateModelFromProjection($entity, $data);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Uaktualniono urządzenie "%s".', $entity->getName()));

            return $this->redirectToRoute('tracker');
        }

        return $this->render('tracker/edit.html.twig', [
            'entity'        => $entity,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/emulator/reset", name="tracker_emulator_reset", requirements={"id"="^[a-f0-9\-]+$"}, methods="GET|POST")
     * @ParamConverter("tracker")
     */
    public function emulatorReset(
        Request $request,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        Tracker $entity
    ): Response
    {

        $entity->resetEmulator();
        $entityManager->flush();

        $flashBag->add('success', sprintf('Zresetowano emulator urządzenia "%s".', $entity->getDevice()->getName()));

        return $this->redirectToRoute('tracker_edit', ['id' => $entity->getId()]);
    }
}