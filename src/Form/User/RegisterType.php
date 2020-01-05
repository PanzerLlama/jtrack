<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Validator\Constraints\UniqueUser;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'         => 'E-mail',
                'constraints'   => [
                    new NotBlank(), new Email(), new UniqueUser()
                ],
            ])->add('plainPassword', RepeatedType::class, [
                'type'              => PasswordType::class,
                'constraints'       => new NotBlank(),
                'first_options'     => [
                    'label' => 'Hasło'
                ],
                'second_options'    => [
                    'label' => 'Powtórz hasło'
                ],
            ])->add('name', TextType::class, [
                'label'         => 'Imię i nazwisko',
                'constraints'   => [
                    new NotBlank()
                ]
            ])
        ;
    }
}
