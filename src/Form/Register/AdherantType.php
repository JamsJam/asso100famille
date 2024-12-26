<?php

namespace App\Form\Register;

use App\Entity\FamilleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AdherantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                "attr" => [
                    "class" => 'text-type'
                ]
            ])
            ->add('prenom',TextType::class,[
                "attr" => [
                    "class" => 'text-type'
                ]
            ])
            ->add('dateDeNaissance',DateType::class,[
                'widget' => 'single_text',
                'input' => 'string',
                // "html5" => false,
                "attr" => [
                    "class" => 'text-type'
                ]
            ])
            ->add('Profession',TextType::class,[
                "attr" => [
                    "class" => 'text-type'
                ]
            ])

            ->add('FamilleType',ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "Votre type de famille" => "",
                    "traditionnel" => "traditionnelle",
                    "nucléaire" => "nucleaire",
                    "pacsé" => "pacsee",
                ],
                'multiple' => false,
                'expanded' => false,
                "attr" => [
                    "class" => 'select-type'
                ]
            ])
            ->add('FamilleNombre',NumberType::class, [
            'label' => 'Votre famille est composé de :',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleAdulte',NumberType::class, [
            'label' => 'Combien d\'adultes ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleHomme',NumberType::class, [
            'label' => 'Combien d\'hommes ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleFemme',NumberType::class, [
            'label' => 'Combien de femmes ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleMineur',NumberType::class, [
            'label' => 'Combien de mineurs ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleGarcon',NumberType::class, [
            'label' => 'Combien de garçons ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
        ])
            ->add('FamilleFille',NumberType::class, [
            'label' => 'Combien de filles ?',
            'input'=> 'string',
            'attr' => [
                'readonly' => true,
                'value' => 0,
                'class' => 'counter-type'
            ]
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
