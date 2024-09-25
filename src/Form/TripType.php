<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom',
            ])
            ->add('destination',TextType::class, [
                'label' => 'Destination',
            ])
            ->add('description',TextType::class, [
                'label' => 'Description',
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
            ],DateType::class, [
                'label' => 'Date de début',
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ],DateType::class, [
                'label' => 'Date de fin',
            ])
            ->add('budget',IntegerType::class, [
                'label' => 'Budget',
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,  // Permet de ne pas forcer la modification de l'image
                'allow_delete' => true, // Permet de supprimer l'image
                'download_uri' => false, // Désactive l'URI de téléchargement
                'image_uri' => true, // Active l'affichage de l'image
                'label' => 'Image du voyage (JPG/PNG)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }

    
}
