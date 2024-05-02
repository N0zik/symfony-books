<?php

namespace App\Form;

use App\Entity\Livres;
use App\Entity\Emprunts;
use App\Entity\Utilisateurs;
use App\Entity\CommentairesEmprunts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesEmpruntsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire')
            ->add('emprunts', EntityType::class, [
                'class' => Emprunts::class,
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
