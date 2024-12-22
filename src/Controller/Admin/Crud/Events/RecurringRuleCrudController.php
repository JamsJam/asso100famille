<?php

namespace App\Controller\Admin\Crud\Events;

use App\Entity\RecurringRule;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecurringRuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecurringRule::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            FormField::addRow(),
            ChoiceField::new('daysOfWeek', 'Tous les ...')
                ->allowMultipleChoices(false)
                ->renderExpanded(true)
                ->setChoices([
                    'Lundi' => 'monday',
                    'Mardi' => 'tuesday',
                    'Mercredi' => 'wednesday',
                    'Jeudi' => 'thursday',
                    'Vendredi' => 'friday',
                    'Samedi' => 'saturday',
                    'Dimanche' => 'sunday',
                ])
                ->setFormTypeOptions([
                    'attr' => [
                        'style' => 'display:flex;justify-content:start;flex-wrap:wrap;align-items:center;gap:1rem', 
                        ]
                        ,
                    ])
                ->setRequired(true)
                ->setColumns(6),

            BooleanField::new('isActive', 'Actif ')
                ->setRequired(false)
                ->setColumns(6)
                // ->hideOnForm(),
    
        ];
    }
    

}
