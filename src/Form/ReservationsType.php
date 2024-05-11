<?php

namespace App\Form;

use App\Entity\Reservations;
use App\Entity\Utilisateurs;
use App\Entity\SallesTravail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateTimeType::class, [
                'date_widget' => 'single_text',
                'hours' => range(8, 19),
                'minutes' => range(0, 59, 15)
            ])
            ->add('dateFin', DateTimeType::class, [
                'date_widget' => 'single_text',
                'hours' => range(8, 19),
                'minutes' => range(0, 59, 15)
            ])
            ->add('utilisateurs', EntityType::class, [
                'class' => Utilisateurs::class,
                'choice_label' => 'id',
                'attr' => [
                    'style' => 'display: none;',
                ],
                'label' => false
            ])
            ->add('sallesTravail', EntityType::class, [
                'class' => SallesTravail::class,
                'choice_label' => 'id',
                'attr' => [
                    'style' => 'display: none;',
                ],
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
