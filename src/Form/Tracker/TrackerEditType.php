<?php

declare(strict_types=1);

namespace App\Form\Tracker;

use App\Form\Traits\SubmitButton;
use App\Validator\Constraints\UniqueUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class TrackerEditType extends AbstractType
{
    use SubmitButton;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'     => 'Nazwa'
        ])->add('uid', TextType::class, [
            'label'         => 'Unikalny id',
            'constraints'   => [
                new NotBlank(),
                new Regex([
                    'pattern'   => '/^[a-z0-9\-]+$/i',
                    'message'   => 'Unikalny id trackera musi składać się tylko ze znaków alfanumerycznych i znaku "-".'
                ])
            ]
        ])->add('secret', TextType::class, [
            'label'     => 'Sekret'
        ])->add('enabled', CheckboxType::class,[
            'label'     => 'urządzenie jest aktywne',
            'required'  => false
        ])->add('flagEmulated', CheckboxType::class,[
            'label'     => 'emuluj',
            'required'  => false
        ]);

        $this->addSubmitButton($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'autocomplete' => 'off'
            ]
        ]);
    }
}
