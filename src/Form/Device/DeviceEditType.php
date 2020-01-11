<?php

declare(strict_types=1);

namespace App\Form\Device;

use App\Form\Traits\SubmitButton;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DeviceEditType extends AbstractType
{
    use SubmitButton;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'     => 'Nazwa'
        ])->add('trackerUid', TextType::class, [
            'label'     => 'Identyfikator trackera',
        ])->add('color', TextType::class, [
            'label'     => 'Kolor ikony (mapa)'
        ])->add('secret', TextType::class, [
            'label'     => 'Sekret'
        ])->add('enabled', CheckboxType::class,[
            'label'     => 'urządzenie jest aktywne',
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
