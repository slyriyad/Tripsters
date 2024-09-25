<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('email', EmailType::class ,[
    'label' => 'E-mail',
])
->add('name', TextType::class,[
    'label' => 'Pseudo',
])
->add('agreeTerms', CheckboxType::class, [
'mapped' => false,
'constraints' => [
new IsTrue([
'message' => 'You should agree to our terms.',
]),
],
])
->add('plainPassword', RepeatedType::class, [
    'type' => PasswordType::class,
    'first_options' => [
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
            new NotBlank([
                'message' => 'Le mot de passe ne peut pas être vide',
            ]),
            new Length([
                'min' => 12,
                'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                'max' => 255,
                'maxMessage' => 'Votre mot de passe ne peut pas dépasser {{ limit }} caractères',
            ]),
            new Regex([
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!%*.?&])[A-Za-z\d@!.%*?&]{12,}$/',
                'message' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial.',
            ]),
        ],
        'label' => 'Mot de passe',
    ],
    'second_options' => [
        'attr' => ['autocomplete' => 'new-password'],
        'label' => 'Confirmer mot de passe',
    ],
    'invalid_message' => 'The password fields must match.',
    'mapped' => false, // Ne pas mapper à l'entité car il n'y a pas de champ correspondant
])
->add('imageFile', VichImageType::class, [
'required' => false,
'allow_delete' => true,
'delete_label' => "Supprimer l'image",
'download_uri' => false,
'image_uri' => true,
'asset_helper' => true,
'label' => 'Photo de profil',
])
;
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
    ]);
}
}