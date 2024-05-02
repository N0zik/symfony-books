<?php

namespace App\Form;

use App\Entity\CommentairesEmprunts;
use App\Entity\Emprunts;
use App\Entity\Utilisateurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesEmpruntsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire')
            ->add('dateAjout', null, [
                'widget' => 'single_text'
            ])
            ->add('emprunts', EntityType::class, [
                'class' => Emprunts::class,
                'choice_label' => 'id',
            ])
            ->add('utilisateurs', EntityType::class, [
                'class' => Utilisateurs::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentairesEmprunts::class,
        ]);
    }
}
