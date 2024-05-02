<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'date_widget' => 'single_text',
            ])
            ->add('salles_travail', ChoiceType::class, [
                'choices' => [
                    'Salle 1' => 'Salle 1',
                    'Salle 2' => 'Salle 2',
                    'Salle 3' => 'Salle 3',
                    // Ajoutez autant de salles que nécessaire
                ],
                'multiple' => true,
                'expanded' => true, // pour afficher les cases à cocher comme des boutons radio
            ])

            ->add('description')
            ->add('all_day')
            ->add('background_color', ColorType::class )
            ->add('text_color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
