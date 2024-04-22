<?php

namespace App\Form;

use App\Entity\Equipements;
use App\Entity\SallesTravail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SallesTravailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //ajouter equipement dans le formulaire
        $builder
            ->add('nom', null, [
                'label' => 'Nom de la salle'
            ])
            ->add('capacite' , null, [
                'label' => 'Capacité de la salle'
            ])
            ->add('disponibilite'
            , null, [
                'label' => 'Disponibilité de la salle'
            ])
            ->add('equipements', EntityType::class, [
                'label' => 'Equipements de la salle',
                'mapped' => false,
                'class' => Equipements::class,
                'choice_label' => 'id',
                'multiple' => true,
           ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SallesTravail::class,
        ]);
    }
}
