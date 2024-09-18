<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
class RegistrationFormType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('email', EmailType::class)
->add('name', TextType::class)
->add('agreeTerms', CheckboxType::class, [
'mapped' => false,
'constraints' => [
new IsTrue([
'message' => 'You should agree to our terms.',
]),
],
])
->add('plainPassword', PasswordType::class, [
'mapped' => false,
'attr' => ['autocomplete' => 'new-password'],
'constraints' => [
new NotBlank([
'message' => 'Please enter a password',
]),
new Length([
'min' => 6,
'minMessage' => 'Your password should be at least {{ limit }} characters',
'max' => 4096,
]),
],
])
->add('imageFile', VichImageType::class, [
'required' => false,
'allow_delete' => true,
'delete_label' => "Supprimer l'image",
'download_uri' => false,
'image_uri' => true,
'asset_helper' => true,
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