<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 11/17/18
 * Time: 12:28 PM
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\User\UserCreateType;
use App\Form\User\UserEditType;
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
 * @Route("/user")
 */
class UserController extends BaseController
{
    public function _init()
    {
        $this->addBreadcrumb($this->generateUrl('app_index'), 'Strona główna');
        $this->addBreadcrumb($this->generateUrl('user'), 'Użytkownicy');
    }

    /**
     * @Route("", name="user")
     * @Template()
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')->from(User::class, 'u');

        $pagination = $paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1), 20);

        return [
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'pagination'    => $pagination
        ];
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
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
        $this->addBreadcrumb($this->generateUrl('user_new'), 'Nowy użytkownik');

        $user = new User();

        $form = $formFactory->createNamed('', UserCreateType::class, [
            'email'         => '',
            'name'          => '',
            'plainPassword' => '',
            'enabled'       => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $projectionService->updateModelFromProjection($user, $data);

            if ($user->getPlainPassword()) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Utworzono użytkownika "%s".', $data['email']));

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'user'          => $user,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_edit", requirements={"id"="^[a-f0-9\-]+$"}, methods="GET|POST")
     * @ParamConverter("user")
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        UserPasswordEncoderInterface $passwordEncoder,
        ProjectionService $projectionService,
        User $user
    ): Response
    {
        $this->addBreadcrumb($this->generateUrl('user_edit', ['id' => $user->getId()]), sprintf('%s', $user->getName()));

        $form = $formFactory->createNamed('', UserEditType::class, $projectionService->getProjection($user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $projectionService->updateModelFromProjection($user, $data);

            if ($user->getPlainPassword()) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            }

            //$entityManager->persist($user);
            $entityManager->flush();

            $flashBag->add('success', sprintf('Uaktualniono użytkownika "%s".', $data['email']));

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'user'          => $user,
            'breadcrumbs'   => $this->getBreadcrumbs(),
            'form'          => $form->createView(),
        ]);
    }
}