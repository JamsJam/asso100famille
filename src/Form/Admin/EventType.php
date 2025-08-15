<?php

namespace App\Form\Admin;

use App\DTO\Evenement\EventDTO;
use DateTimeImmutable;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $theme = $options['theme'];
        $builder
            ->add('titre',TextType::class,[
                "label"=> "Titre de l'évènement",
                // "help"=> "titre de l'evenement",
                "help_attr" => [
                    "class"=>"form__help form__help--". $theme
                ],
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "data-dynamic-form-submit-target" => 'sacrifacedInput',
                    "placeholder" => "Titre",
                    "maxlength"=>100,
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                //?-------------


                //?-------------
                'constraints' => [
                    new NotBlank(),
                    new Type(
                        type:'string',
                        message:'Veuillez entrer une valeur au bon format'
                    ),
                    new NoSuspiciousCharacters(
                        checks: NoSuspiciousCharacters::CHECK_HIDDEN_OVERLAY,
                        restrictionLevel: NoSuspiciousCharacters::RESTRICTION_LEVEL_HIGH,
                    ),
                    new Length(
                        max:100,
                        maxMessage:'Veuillez ne pas dépasser les {{ max }} caracteres'
                    )

                ],
            ])
            ->add('description',TextareaType::class,[
                "label" => "Description de l'évènement",
                "help" => "150 caracteres max",
                "help_attr" => [
                    "class"=>"form__help form__help--". $theme,
                ],
                "attr" => [
                    'class' => 'form__input-textarea form__input-textarea--' . $theme,
                    "maxlength"=>150,
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                 //?-------------


                //?-------------
                'constraints' => [
                    new NotBlank(),
                    new Type(
                        type:'string',
                        message:'Veuillez entrer une valeur au bon format'
                    ),
                    new NoSuspiciousCharacters(
                        checks: NoSuspiciousCharacters::CHECK_HIDDEN_OVERLAY,
                        restrictionLevel: NoSuspiciousCharacters::RESTRICTION_LEVEL_HIGH,
                    ),
                    new Length(
                        max:150,
                        maxMessage:'Veuillez ne pas dépasser les {{ max }} caracteres'
                    )

                ],
            ])
            ->add('image',FileType::class,[

                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                'multiple'=> false,
                'required' =>false,
                'data_class' => null,

                'constraints' => [

                    new Image(
                        detectCorrupted: true,
                        corruptedMessage: "Cette image est corrompu, veuillez en choisir une autre",
                        maxSize: "2M",
                        maxSizeMessage: "Cette image est trop lourd ({{ size }} {{ suffix }}). Veuillez entrer une image de moins de {{ limit }} {{ suffix }}",
                    )
                ],
            ])
            ->add('isFree',ChoiceType::class,[
                'required' => true,
                'multiple'=>false,
                'expanded'=>true,
                'choices'=>[
                    'Gratuit' => true,
                    'Payant' => false
                ],
                "label"=>false,
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "row_attr" =>[
                    
                ],
                // 'constraints' => [new NotBlank()],
            ])
            ->add('prix',MoneyType::class,[
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 40",
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],

                "label"=>"Prix pour les non-adherant",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                //?----------------
                'divisor' => 100,
                "rounding_mode"=>\NumberFormatter::ROUND_FLOOR,
                //?----------------
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ])
            ->add('userPrix',MoneyType::class,[
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 40"
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                "label"=>"Prix pour les adherant",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],

                //?----------------
                'divisor' => 100,
                "rounding_mode"=>\NumberFormatter::ROUND_FLOOR,
                //?----------------
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ])
            ->add('eventType',ChoiceType::class,[
                'label'=>false,
                'multiple'=>false,
                'expanded'=>true,
                'choices'=>[
                    'Evenement ponctuel' => 'ponctuel',
                    'Evenement récurant' => 'recurring'
                ],
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                    
                ],
                
                'constraints' => [new NotBlank()],
            ])
            ->add('recurringRule',RecurringRulesType::class,$this->getOptions('recurringRule', $theme))
            
            
            
            ->add('startAt',DateType::class,[
                "label" => "Jour de début de l'evenement",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "help" => "Date au format dd/mm/yyyy",
                "help_attr" =>[
                    "class" => "form__help form__help--" . $theme
                ],
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 20/02/2022"
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                //?---------------
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                "input" => 'string',
                'input_format' => 'd/m/Y',
                //?---------------
                'constraints' => [
                    new NotBlank(),
                    // new GreaterThan([
                    //     'value' => new \DateTimeImmutable(),
                    //     'message' => 'La date doit être postérieure à demain.'
                    // ])
                ],
            ])
            ->add('startHour',TimeType::class,[
                "label" => "Heure de debut de l'evenement",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "help" => "Heure au format hh:mm",
                "help_attr" =>[
                    "class" => "form__help form__help--" . $theme,
                    'placeholder' => "exemple : 20:30 "
                ],
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    'placeholder' => "exemple : 20:30 "
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                //?----------------
                'html5' => false,
                'widget' => 'single_text',
                // 'format' => 'dd/MM/y',
                "input" => 'string',
                'input_format' => 'H:i',
                //?----------------
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('endAt',DateType::class,[
                "label" => "Jour de fin de l'evenement",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "help" => "Date au format dd/mm/yyyy",
                "help_attr" =>[
                    "class" => "form__help form__help--" . $theme
                ],
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 20/02/2022"
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                //?---------------
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                "input" => 'string',
                'input_format' => 'd/m/Y',
                //?---------------
                'constraints' => [
                    new NotBlank(),
                    // new GreaterThan(
                    //     value: new \DateTime(),
                    //     message: 'La date doit être postérieure à demain.'
                    // )
                ],
            ])
            ->add('endHour',TimeType::class,[
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    'placeholder' => "exemple : 20:30 "
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                ],
                
                "label"=> "Heure de fin de l'evenement",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],

                "help" => "Heure au format hh:mm",
                "help_attr" =>[
                    "class" => "form__help form__help--" . $theme
                ],
                //?----------------
                'html5' => false,
                'widget' => 'single_text',
                // 'format' => 'dd/MM/y',
                "input" => 'string',
                'input_format' => 'H:i',
                //?----------------
                'constraints' => [
                    new NotBlank()
                ],
            ])



            ->addEventListener(FormEvents::PRE_SET_DATA,[$this,"isRecurring"])
            ->addEventListener(FormEvents::PRE_SET_DATA,[$this,"isFree"])
            ->addEventListener(FormEvents::PRE_SUBMIT,[$this,"isRecurring"])
            ->addEventListener(FormEvents::PRE_SUBMIT,[$this,"isFree"])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();

                if ($data['startAt'] && $data['endAt'] && (new \DateTimeImmutable())->createFromFormat("d/m/Y",$data['endAt']) < (new \DateTimeImmutable())->createFromFormat("d/m/Y",$data['startAt'])) {
                    dump($data['endAt'],$data['startAt'],(new \DateTimeImmutable())->createFromFormat("d/m/Y",$data['endAt']) < (new \DateTimeImmutable())->createFromFormat("d/m/Y",$data['startAt']) );
                    $form->get('endAt')->addError(
                        new FormError('La date de fin doit être après la date de début.')
                    );
                }
                
            });
    }

    //?===== EventListener

    public function isRecurring(FormEvent $event){
        $form = $event->getForm();
        $theme = $event->getForm()->getConfig()->getOption('theme');
        $evenement = $event->getdata();

        if(!isset($evenement['eventType'])){
            $form->remove('recurringRule');
            return;
        }

        if( $evenement['eventType'] === 'ponctuel'){
            $form->remove('recurringRule');
        }elseif( $evenement['eventType'] === 'recurring' ){
            $form->add('recurringRule',RecurringRulesType::class,$this->getOptions('recurringRule', $theme));
        }
    }
    public function isFree(FormEvent $event){
        $form = $event->getForm();
        $theme = $event->getForm()->getConfig()->getOption('theme');
        $evenement = $event->getdata();
        
        if(!isset($evenement['isFree'])){
            $form->remove('prix');
            $form->remove('userPrix');

            return;
        }

        if( $evenement['isFree'] ){
            $form->remove('prix');
            $form->remove('userPrix');
        }else{
            $form->add('prix',MoneyType::class,$this->getOptions('prix', $theme));
            $form->add('userPrix',MoneyType::class,$this->getOptions('userPrix', $theme));
            
        }
    }

    public function getOptions(string $field, string $theme ): array
    {

        $options = match ($field) {
            'prix' =>  [
                
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 40",
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                    "id" => "prix",
                ],
                "label"=>"Prix pour les non-adherant",
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                "help"=> "Prix de l'evenement en euro",
                "help_attr" => [
                    "class"=>"form__help form__help--". $theme
                ],
                //?----------------
                'divisor' => 100,
                "rounding_mode"=>\NumberFormatter::ROUND_FLOOR,
                //?----------------
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ],
            'userPrix' => [
                "label"=>"Prix pour les non-adherant",
                "attr" => [
                    'class' => 'form__input-text form__input-text--' . $theme,
                    "placeholder" => "exemple : 40",
                ],
                "help"=> "Prix de l'evenement en euro",
                "help_attr" => [
                    "class"=>"form__help form__help--". $theme
                ],
                "row_attr" => [
                    'class' => 'form__row form__row--'. $theme,
                    'id' => 'userPrix'
                ],
                "label_attr" =>[
                    "class" => "form__label form__label--" . $theme
                ],
                //?----------------
                'divisor' => 100,
                "rounding_mode"=>\NumberFormatter::ROUND_FLOOR,
                //?----------------
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],

            ],
            'recurringRule' => [
                "label"=>"Regle de récurence",
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
                'constraints' => [new NotBlank()],
            ],
        };
        return $options;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'theme' => null,
            "attr" => [
                "data-controller"=>"dynamic-form-submit",
                "data-dynamic-form-submit-id-to-search-value"=> json_encode([
                    ["isfreeRow" =>"prix"],
                    ["isfreeRow"=>"userPrix"],
                    ["typeEventRow"=>"rules"]
                ]),

            ] 
        ]);
    }
}
