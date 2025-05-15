<?php

namespace App\Controller\Admin\Crud\Events;

use App\Entity\OneTimeEvent;
use App\Service\MailerService;
use App\Repository\OneTimeEventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OneTimeEventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OneTimeEvent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ->setSearchMode(SearchMode::ALL_TERMS)
            ->setPageTitle('detail', fn (OneTimeEvent $oneTimeEvent) => (string) $oneTimeEvent->getTitle())
            ->setFormOptions([
                'attr' => ['data-controller' => 'cropper']
            ])
            ->setFormThemes(['admin/form.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {

        yield FormField::addFieldset('Date');
        yield TextField::new('title', 'Titre')
        ->setSortable(true);
        if(Crud::PAGE_INDEX == $pageName){

            yield DateField::new('createdAt', 'Créé le')
                ->setSortable(true);
        }


        yield TextEditorField::new('description', 'Description')
            ->setSortable(true);

        //? ==========================================
        //! SECTION: Dates et horaire
        //? ==========================================
            yield FormField::addFieldset('Dates et horaire de l\'évenement');
            yield FormField::addRow();
            yield DateField::new('startDate', 'Date de l\'evenement')
                ->setFormTypeOptions([
                    "input" => "datetime_immutable"
                ])
                ->setColumns(6)
                ->setSortable(true);

        //? ==========================================
        //! SECTION: Heure de l'événement
        //? ==========================================
            yield FormField::addRow();
            yield TimeField::new('startHour', 'Heure de début')
                ->setColumns(6)
                ->setSortable(true);

            yield TimeField::new('endHour', 'Heure de fin')
                ->setColumns(6)
                ->setSortable(true);

        //? ==========================================
        //! SECTION: Prix de l'événement
        //? ==========================================
            yield FormField::addFieldset('Prix de l\'évenement');
            yield BooleanField::new('isFree', 'Evenement gratuit ?');

            yield FormField::addRow();
            yield MoneyField::new('price', 'Prix')
                ->setColumns(6)
                ->setCurrency('EUR')
                ->setNumDecimals(2);

            yield MoneyField::new('userPrice', 'Prix Abonné')
                ->setColumns(6)
                ->setCurrency('EUR')
                ->setNumDecimals(2);

        //? ==========================================
        //! SECTION: Réservation
        //? ==========================================
            if(Crud::PAGE_DETAIL == $pageName){

                yield FormField::addFieldset('Réservation');
                yield CollectionField::new('reservations', false)->setTemplatePath('admin/fields/event_reservations.html.twig');
            }

        //? ==========================================
        //! SECTION: Image
        //? ==========================================
            yield FormField::addFieldset('Image');
            yield FormField::addRow();
            yield ImageField::new('image')
                ->setBasePath('/images/upload/events')
                ->setUploadDir('public/images/upload/events')
                ->setUploadedFileNamePattern('[timestamp]-[randomhash].[extension]')
                ->setFormTypeOptions([
                    'required' => false,
                    'attr' => [
                        "data-action" => "change->cropper#loadImage"
                    ]
                ])
                ->setColumns(6);

            yield TextField::new('crop', 'Aperçu')
                ->setFormTypeOptions([
                    'mapped' => 'false',
                    'block_name' => 'crop_image'
                ])
                ->onlyOnForms()
                ->setColumns(6);
    }



    

    public function configureActions(Actions $actions): Actions
    {
        $cancelAction = Action::new('cancelEvent','Annuler');
            // renders the action as a <a> HTML element
            $cancelAction->displayAsLink()

            // a key-value array of attributes to add to the HTML element
            ->setHtmlAttributes(['data-foo' => 'bar', 'target' => '_blank'])
            // removes all existing CSS classes of the action and sets
            // the given value as the CSS class of the HTML element
            ->setCssClass('btn btn-primary action-foo')
            // adds the given value to the existing CSS classes of the action (this is
            // useful when customizing a built-in action, which already has CSS classes)
            ->addCssClass('some-custom-css-class text-danger')

            //? methode parameters
            ->linkToCrudAction('cancelEvent')
            // //? route parameters
            // ->linkToRoute('admin_re_cancel')
        ;
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
            ->update(Crud::PAGE_INDEX, Action::EDIT,function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Modifier');
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

    #[AdminAction(routePath: 'admin/cancel', routeName: 'cancel', methods: ['GET','POST'] )]
    public function cancelEvent(AdminContext $context, MailerService $mailerService)
    {
        
        
        // get the class name
        $className = $context->getEntity()->getFqcn();
        // get id and formData from POST parameters
        $form = $context->getRequest()->request;
        $id = $form->get('entityId');
        $isAllCancel = $form->get('isAllCancel');

        $dateEvent = $form->get('eventdate');
        //get the entity Manager
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);

        $currentEntity = $entityManager->find($className,$id);
        // dd($currentEntity->getReservations());
        $currentEntity->setStatus("cancel");
        foreach ($currentEntity->getReservations() as $reservation) {
            $adherentMail = $reservation->getEmail();
            $adherentNom = $reservation->getNom();
            $adherentPrenom = $reservation->getPrenom();
            // mail pour evenement annulé
            $mailerService->sendTemplatedMail(
                'contact@tiers-lieu100p100famille.fr',
                $adherentMail,
                'Evenement Annulé',
                'admin/cancel.html.twig',
                [
                    "event_name" => 'currentEntity',
                    "user_fullname" => $adherentNom.' '.$adherentPrenom,
                    "event_date" => $dateEvent,
                    "isAllCancel" => $isAllCancel
                ]
            );
        }

        



        $entityManager->persist($currentEntity);
        $entityManager->flush();

        return $this->redirectToRoute('admin');

    }
}
