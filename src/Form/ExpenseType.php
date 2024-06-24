<?php

namespace App\Form;

use App\Entity\CategoryExpense;
use App\Entity\Expense;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('descrition')
            ->add('name')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
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
