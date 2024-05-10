<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FusionnerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('calendar', CalendarType::class, [
                'label' => false, // Masquer la légende
            ])
            ->add('reservations', ReservationsType::class, [
                'label' => false, // Masquer la légende
            ])
        ;
    }
}