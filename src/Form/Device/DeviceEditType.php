<?php

declare(strict_types=1);

namespace App\Form\Device;

use App\Entity\Tracker;
use App\Form\Traits\SubmitButton;
use App\Form\Transformer\TrackerProjectionToIdTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DeviceEditType extends AbstractType
{
    use SubmitButton;

    /**
     * @var array
     */
    private $trackerChoices = [
        'Brak'  => ''
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'     => 'Nazwa'
        ])->add('color', TextType::class, [
            'label'     => 'Kolor ikony (mapa)'
        ])->add('tracker', ChoiceType::class, [
            'label'     => 'Tracker',
            'required'  => false,
            'choices'   => $this->getTrackerChoices($options['entityManager'])
        ])->add('enabled', CheckboxType::class,[
            'label'     => 'urządzenie jest aktywne',
            'required'  => false
        ]);

        $builder->get('tracker')->addModelTransformer(new TrackerProjectionToIdTransformer($options['entityManager']));

        $this->addSubmitButton($builder);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return array
     */
    private function getTrackerChoices(EntityManagerInterface $entityManager) {

        if (count($this->trackerChoices) == 1) {

            $trackers = $entityManager->getRepository(Tracker::class)->findBy([], ['name' => 'ASC']);

            /** @var Tracker $tracker */
            foreach ($trackers as $tracker) {

                $label = $tracker->getName();

                if (!$tracker->isEnabled()) {
                    $label .= ' (wyłączony)';
                }

                if ($tracker->isFlagEmulated()) {
                    $label .= ' (emulowany)';
                }

                $this->trackerChoices[$label] = $tracker->getId();
            }
        }

        return $this->trackerChoices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'autocomplete' => 'off'
            ]
        ]);

        $resolver->setRequired([
            'entityManager'
        ]);
    }
}
