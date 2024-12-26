<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use Twig\Attribute\YieldReady;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        //? ========================  Detail:page
        if ($pageName === Crud::PAGE_DETAIL) {
            # code...

        
            //? -----------------------
            //? -----------------------
            yield FormField::addTab('Mes informations');
            yield FormField::addrow();
            yield FormField::addColumn(6);
            yield IdField::new('nom','Nom :');
            yield FormField::addColumn(6);
            yield TextField::new('prenom','Prenom :');
            yield FormField::addColumn(12);
            yield DateField::new('ddn',"Date de naissance :")
            ->setFormat('dd/MM/YYYY')
            ;
            yield FormField::addrow();
            yield FormField::addColumn(6);
            yield TelephoneField::new('telephone',"Téléphone :")
            
            ;
            yield FormField::addColumn(6);
            yield EmailField::new('email',"E-mail :");
            
            //? -----------------------
            //? -----------------------
            yield FormField::addTab('Ma famille');
            
            yield AssociationField::new('famille');
            
            
            
            
            //? -----------------------
            //? -----------------------
            yield FormField::addTab('Mon abonement');
            // yield AssociationField::new('abonement');
            // yield MoneyField::new('prix');
            yield DateField::new('createdAt');
            yield DateField::new('expiredAt');
            yield TextField::new('status');
            




            //? -----------------------
            //? -----------------------
            yield FormField::addTab('Mes reservations');
            
            yield CollectionField::new('reservation',false)
                ->setTemplatePath("admin/fields/users_reservations.html.twig")
            ;
            
            //? -----------------------
            //? -----------------------
            yield FormField::addTab('Mon historique de reservations');
            yield CollectionField::new('reservation',false)
                ->setTemplatePath("admin/fields/users_historique_reservations.html.twig")
            ;

        
        } elseif ($pageName === Crud::PAGE_INDEX) {
            //? ========================  index:page
            yield FormField::addTab('Mes informations');
            yield FormField::addrow();
            yield FormField::addColumn(6);
            yield IdField::new('nom','Nom');
            yield FormField::addColumn(6);
            yield TextField::new('prenom','Prenom');
            yield FormField::addColumn(12);
            yield DateField::new('ddn',"Date de naissance")
            ->setFormat('dd/MM/YYYY')
            ;
            yield FormField::addrow();
            yield FormField::addColumn(6);
            yield TelephoneField::new('telephone',"Téléphone")
            
            ;
            yield FormField::addColumn(6);
            yield EmailField::new('email',"E-mail");
        


        
        } elseif ($pageName === Crud::PAGE_EDIT) {
            //? ========================  Edit:page
            # code...
        
        
        
        
        }

        
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
        
        //! ==========================  Index page
            ->add(Crud::PAGE_INDEX, Action::DETAIL)


            //? ===== label index actions
            ->update(Crud::PAGE_INDEX, Action::DETAIL,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Details');
            })
            ->update(Crud::PAGE_INDEX, Action::NEW,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Ajouter');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Supprimer');
            })

        //! ==========================  Details page

            // ->add(Crud::PAGE_DETAIL, Action::INDEX)


            //? ===== label detail actions
            ->update(Crud::PAGE_DETAIL, Action::EDIT,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Modifier');
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::INDEX,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Retour');
            })


            -> reorder(Crud::PAGE_DETAIL, [Action::DELETE,Action::INDEX,Action::EDIT])

        //! ==========================  New page
            ->add(Crud::PAGE_NEW, Action::INDEX)

            ->update(Crud::PAGE_NEW, Action::INDEX, function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Annuler');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Enregistrer et continuer');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Enregistrer');
            })


        //! ==========================  Edit page
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::DELETE)



            ->update(Crud::PAGE_EDIT, Action::INDEX,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Annuler');
            })
            ->update(Crud::PAGE_EDIT, Action::DELETE,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Valider');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Valider et quitter');
            })
            // ->update(Crud::PAGE_INDEX, Action::DELETE,function (Action $action) {
            //     return $action->setIcon('fa fa-file-alt')->setLabel('Supprimer');
            // })

        ;
    }

}
