<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\SallesTravail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text',
                'hours' => range(8, 19),
                'minutes' => range(0, 59, 15),
                'label' => 'Date début'
            ])
            ->add('end', DateTimeType::class, [
                'date_widget' => 'single_text',
                'hours' => range(8, 19),
                'minutes' => range(0, 59, 15),
                'label' => 'Date fin'
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
