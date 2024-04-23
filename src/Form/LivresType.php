<?php

namespace App\Form;

use App\Entity\Livres;
use App\Entity\Auteurs;
use App\Entity\EtatsLivres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('anneePublication')
            ->add('resume')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'download_label' => 'Télécharger',
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('disponibilite', CheckboxType::class, [
                'label' => 'Disponible',
                'data' => true,
            ])
            ->add('etatsLivres', EntityType::class, [
                'class' => EtatsLivres::class,
                'choice_label' => 'libelle',
            ])
            ->add('auteurs', EntityType::class, [
                'class' => Auteurs::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                            ])
                        ;
                    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
