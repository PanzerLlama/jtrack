<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 12/24/18
 * Time: 11:53 AM
 */

namespace App\Form\Traits;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

trait SubmitButton
{
    public function addSubmitButton(FormBuilderInterface $builder, $label = 'Zapisz')
    {
        $builder->add('action_submit', SubmitType::class, [
            'label'         => $label,
            'attr'          => [
                'class'		=> 'btn'
            ]
        ]);
    }
}