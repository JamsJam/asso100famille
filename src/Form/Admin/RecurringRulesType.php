<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\SessionBagProxy;

class RecurringRulesType extends AbstractType
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $theme = $this->requestStack->getSession()->get('theme');

        $builder
            ->add('dayOfWeek', ChoiceType::class, [
                "multiple" => false,
                "expanded" => false,
                "choices" =>[
                    'lundi'    => 'Monday',
                    'mardi'    => 'Tuesday',
                    'mercredi' => 'Wednesday',
                    'jeudi'    => 'Thursday',
                    'vendredi' => 'Friday',
                    'samedi'   => 'Saturday',
                    'dimanche' => 'Sunday',
                ],
                "label"=>"Tous les...",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                    'id' => 'rules'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'theme' => null,
        ]);
    }
}
