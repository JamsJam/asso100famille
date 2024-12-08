<?php

namespace App\Form\Register;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // Adhésion Famille with increment/decrement
        ->add('adhesionFamille', NumberType::class, [
            'label' => 'Adhésion famille',
            'help' => '(Licence FGP 2024/2025)',
            // 'html5'=> true,
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 1
            ]

        ])

        // Adhésion Bienfaiteur with increment/decrement
        ->add('adhesionBienfaiteur', NumberType::class, [
            'label' => 'Adhésion bienfaiteur',
            'help' => '(Licence BGP 2024/2025)',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 1
            ]
        ])
        ->add('adhesionBienfaiteurPrix', NumberType::class, [
            'label' => false,
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 80,
                'min' => 80
            ]
        ])

        // Donation options including custom amount
        ->add('don', ChoiceType::class, [
            'label' => 'Souhaitez-vous faire un don à Association 100% Famille en plus de votre adhésion ?',
            'choices' => [
                'Pas de don' => 0,
                '5€' => 5,
                '10€' => 10,
                '20€' => 20,
                'Montant de choix' => null, // Custom amount option
            ],
            'choice_attr' => [
                'Pas de don' => ['checked'=> true],
                'Montant de choix' => [
                    'data-formregister-target' => 'radioCustom'
                ],
            ],
            'expanded' => true, // For radio button display
            'multiple' => false,
        ])

        // Field for custom donation amount if the user selects "Montant de choix"
        ->add('customAmount', NumberType::class, [
            'label' => false,
            'help' => 'Saisir le montant',
            'required' => false,
            'attr' => [
                'placeholder' => 'Saisir le montant',
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
