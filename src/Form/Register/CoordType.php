<?php

namespace App\Form\Register;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CoordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Telephone',TextType::class,[
                'label'=> "Numéro de téléphone",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
            ->add('Adresse',TextType::class,[
                'label'=> "Adresse",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
            ->add('Complement_adresse',TextType::class,[
                'label'=> "Complément d'adresse",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
            ->add('Code_postal',TextType::class,[
                'label'=> "Code postal",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
            ->add('Ville',TextType::class,[
                'label'=> "Ville",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
            ->add('Password',RepeatedType::class,[
                'type'=> PasswordType::class,
                'first_options'  => [
                    'label'=> "Mot de passe",
                    'attr' => [
                        'class' => 'text-type'
                    ]
                ],
                'second_options' => [
                    'label'=> "Confirme mot de passe",
                    'attr' => [
                        'class' => 'text-type'
                    ]
                ],
            ])
            ->add('email',EmailType::class,[
                'label'=> "Email",
                'attr'=>[
                    'class'=>'text-type'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
