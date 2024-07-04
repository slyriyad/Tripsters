<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Expense;
use App\Entity\CategoryExpense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount',IntegerType::class)
            ->add('description',TextType::class)
            ->add('name',TextType::class)
            ->add('date', null, [
                'widget' => 'single_text',
            ],DateType::class)
            ->add('trip', EntityType::class, [
                'class' => Trip::class,
                'choice_label' => 'id',
            ])
            ->add('categoryExpense', EntityType::class, [
                'class' => CategoryExpense::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
        ]);
    }
}
