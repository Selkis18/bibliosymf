<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo*",
                "constraints" => [
                    new Length([
                        "min" => 4,
                        "minMessage" => "Le pseudo doit comporter au moins 4 caractères",
                        "max" => 10,
                        "maxMessage" => "Le pseudo doit comporter maximum 10 caractères"
                    ])
                ]
            ] )
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Lecteur" => "ROLE_USER",
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Admin" => "ROLE_ADMIN"
                ],
                "multiple" => true,
                "expanded" => true
            ] )
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe*"
            ] )
            ->add('prenom', TextType::class, [
                "label" => "Prénom",
                "required" => false
            ] )
            ->add('nom',  TextType::class, [
                "required" => false
            ] )
            ->add('cgu', CheckboxType::class, [
                "label" => "J'accepte les conditions générales d'utilisation",
                "mapped" => false, // l'option "mapped" permet de préciser si le champ est lié à la classe Entity qui a servit à créer le formulaire (ici Entity\Abonne). Par défaut "mapped" vaut true. Si on met false, la valeur du champ ne modifiera pas une propriété de l'objet Abonne lié au formulaire.
                "required" => false,
                "constraints" => [
                    new IsTrue([
                        "message" => "Vous devez acceptez les conditions générales d'utilisation"
                    ])
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
