<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo*"
            ] )
            ->add('plainPassword', PasswordType::class, [
                            "label" => "Mot de passe*",
                            'mapped' => false,
                            'constraints' => [
                                new NotBlank([
                                    'message' => 'Le mot de passe est obligatoire',
                                ]),
                                new Length([
                                    'min' => 6,
                                    'minMessage' => 'Votre mot de passe doit contenir minimum 6 caractères',
                                    'max' => 4096,
                                ]),
                            ],
                        ])
             ->add('prenom', TextType::class, [
                "label" => "Prénom",
                "required" => false
            ] )
             ->add('nom', TextType::class, [
                "required" => false
            ] )
            ->add('agreeTerms', CheckboxType::class, [
                            'label' => 'Conditions générales d\'utilisation',
                            'mapped' => false,
                            'constraints' => [
                                new IsTrue([
                                    'message' => 'Vous devez accepctez les conditions générales d\'utilisation.'
                                ]),
                            ],
                        ])
            ->add('enregistrer', SubmitType::class,[
                "attr" => [
                    "class" => "btn btn-info"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
