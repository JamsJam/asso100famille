<?php

namespace App\Form;

use App\Entity\Abonement;
use App\Entity\Adherent;
use App\Entity\Famille;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('isVerified')
            ->add('ddn', null, [
                'widget' => 'single_text',
            ])
            ->add('profession')
            ->add('adresse')
            ->add('adresse2')
            ->add('codepostal')
            ->add('ville')
            ->add('telephone')
            ->add('famille', EntityType::class, [
                'class' => Famille::class,
                'choice_label' => 'id',
            ])
            ->add('abonement', EntityType::class, [
                'class' => Abonement::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}
