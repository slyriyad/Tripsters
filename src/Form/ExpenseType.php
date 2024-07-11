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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la dépense'])
            ->add('amount', MoneyType::class, ['label' => 'Montant', 'divisor' => 100])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('categoryExpense', EntityType::class, [
                'class' => CategoryExpense::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
        ]);
    }
}
