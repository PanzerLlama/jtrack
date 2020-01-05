<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 2:34 PM
 */
declare(strict_types=1);

namespace App\Controller\User;


use App\Controller\BaseController;
use App\Entity\User;
use App\Form\User\RegisterType;
use App\Service\ProjectionService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends BaseController
{
    /**
     * @Route("/register", name="register")
     * @Template("user/register.html.twig")
     */
    public function register(
        Request $request,
        FormFactoryInterface $formFactory,
        ProjectionService $projectionService,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flash,
        UserPasswordEncoderInterface $passwordEncoder
    ) {

        $form = $formFactory->createNamed('Register', RegisterType::class, [
            'email'     => '',
            'password'  => '',
            'name'      => ''
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = new User();

            //$user->setRoles(['ROLE_ADMIN']);

            $projectionService->updateModelFromProjection($user, $form->getData());

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            $flash->add('positive', sprintf('Utworzono konto dla "%s".', $user->getName()));

            return $this->redirectToRoute('app_login');

        }

        return [
            'form' => $form->createView()
        ];
    }
}