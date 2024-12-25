<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\Register\CoordType;
use App\Form\RegistrationFormType;
use App\Service\EncryptionService;
use App\Form\Register\AdherantType;
use App\Form\Register\AdhesionType;
use Symfony\Component\Mime\Address;
use App\Form\Register\ConfirmationType;
use App\Repository\FamilleTypeRepository;
use App\Service\StripeService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{


    public function __construct(

        private EncryptionService $cryptage,
        private EmailVerifier $emailVerifier
    ) {}

    #[Route('/adhesion', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // $user = new User();
        // $form = $this->createForm(RegistrationFormType::class, $user);
        $form = $this->createForm(AdhesionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainData = $form->getData();
            $session = $request->getSession();
            $session->set('register1', $this->cryptRecursively($plainData, 1) );
            // dd($session->get('register1'));

            return $this->redirectToRoute('app_register_adherant');
        }
        $step = 1;
        return $this->render('registration/adhesion.html.twig', [
            'form' => $form,
            'step'=>$step
        ]);
    }

    #[Route('/adhesion/adherant', name: 'app_register_adherant')]
    public function registerAdherant(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('register1')) {
            // Gérer l'erreur, par exemple, en redirigeant vers le formulaire initial
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(AdherantType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainData = $form->getData();
            $session = $request->getSession();
            $session->set('register2', $this->cryptRecursively($plainData, 1) );
            // dd($session->get('register1'),$session->get('register2'));

            return $this->redirectToRoute('app_register_coord');
        }
        $step = 2;
        return $this->render('registration/adherant.html.twig', [
            'form' => $form,
            'step'=>$step
        ]);
    }
    

    #[Route('/adhesion/coord', name: 'app_register_coord')]
    public function registerCoord(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        if (!$session->has('register1') || !$session->has('register2')) {
            // Gérer l'erreur, par exemple, en redirigeant vers le formulaire initial
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(CoordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainData = $form->getData();
            $session = $request->getSession();
            $session->set('register3', $this->cryptRecursively($plainData, 1) );
            // dd($this->cryptRecursively(array_merge($session->get('register1'),$session->get('register2'),$session->get('register3')),2));
            return $this->redirectToRoute('app_register_confirm');
        }

        return $this->render('registration/coordonees.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/adhesion/confirm', name: 'app_register_confirm')]
    public function registerConfirm(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, FamilleTypeRepository $familleTypeRepository,StripeService $stripeService): Response
    {
        $session = $request->getSession();
        if (!$session->has('register1') || !$session->has('register2') || !$session->has('register3')) {
            // Gérer l'erreur, par exemple, en redirigeant vers le formulaire initial
            return $this->redirectToRoute('app_register');
        }
        
        $user = new User();
        $famille = new Famille();

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);
        $decryptSession = $this->cryptRecursively($session->get('register1'), 2);
        $decryptCoord = $this->cryptRecursively($session->get('register2'), 2);
        
        $items = [
            [
                'nom' => 'Adhésion famille',
                'prix' => 3000,
                'quantity' => $decryptSession['adhesionFamille'],
                'total' => 3000 * intval($decryptSession['adhesionFamille']),
                'interval' => 'month',
                'type' => 'subscription',
            ]
        ];
        //? --- if bienfaiter
        if($decryptSession['adhesionBienfaiteur'] > 0){
            array_push($items,            [
                'nom' => 'Adhésion bienfaiteur',
                'prix' => $decryptSession['adhesionBienfaiteurPrix'] * 100,
                'quantity' => $decryptSession['adhesionBienfaiteur'],
                'total' => intVal($decryptSession['adhesionBienfaiteurPrix'] * 100) * intval($decryptSession['adhesionBienfaiteur']),
            ]);
        }
        //? --- if don/customdon
        if ($decryptSession['don'] == null  ) {
            array_push($items,[
                'nom' => 'Don',
                'prix' => $decryptSession['customDon'] * 100,
                'quantity' => 1,
                'total' => intVal($decryptSession['customDon'] * 100) ,
            ]);
        }else if($decryptSession['don'] != 0){
            array_push($items,[
                'nom' => 'Don',
                'prix' => $decryptSession['don'] * 100,
                'quantity' => 1,
                'total' => intVal($decryptSession['don'] * 100),
            ],);

        };


        $frais = [
            'nom' => 'Frais techniques',
            'prix' => 310,
            'quantity' => 1,
            'total' => 310 ,
        ];
        array_push($items,$frais);

        $userName = $decryptCoord['prenom'] .' '. $decryptCoord['nom'];

        if ($form->isSubmitted() && $form->isValid()) {
            
            $sessionFormData = $this->cryptRecursively(array_merge($session->get('register1'),$session->get('register2'),$session->get('register3')),2);

            //?=====Password
                /** @var string $plainPassword */
                $plainPassword = $sessionFormData['Password'];
                
            //?-----------

             //? ===== set user
                $user
                    ->setNom($sessionFormData['nom'])
                    ->setPrenom($sessionFormData['prenom'])
                    ->setEmail($sessionFormData['email'])
                    ->setDdn(new DateTimeImmutable($sessionFormData['dateDeNaissance']))
                    ->setEmail($sessionFormData['email'])
                    ->setAdresse($sessionFormData['Adresse'])
                    ->setAdresse2($sessionFormData['Complement_adresse'])
                    ->setTelephone($sessionFormData['Telephone'])
                    ->setVille($sessionFormData['Ville'])
                    ->setCodepostal($sessionFormData['Code_postal'])
                    ->setProfession($sessionFormData['Profession'])
                    ->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                ;
            //?-----------
            // dd( $sessionFormData);
            // dd($familleTypeRepository->findOneBy(["nom" =>$sessionFormData['FamilleType']]));
            //?========== Set Famille
                $famille
                    ->setType($familleTypeRepository->findOneBy(["nom" =>$sessionFormData['FamilleType']])) 
                    ->setNbFemmes($sessionFormData['FamilleFemme'])
                    ->setNbHommes($sessionFormData['FamilleHomme'])
                    ->setNbGarcons($sessionFormData['FamilleGarcon'])
                    ->setNbFilles($sessionFormData['FamilleFille'])
                    ->setNbAdultes($famille->getNbFemmes() + $famille->getNbHommes())
                    ->setNbMineurs($famille->getNbGarcons() + $famille->getNbFilles())
                    ->setMembre($famille->getNbAdultes() + $famille->getNbMineurs())
                ;
                $user->setFamille($famille);
            //?-----------

            
            //?=====Persist
                $entityManager->persist($user);
                $entityManager->persist($famille);

                // $entityManager->flush();
            //?-----------
            



            //? =======Stripe - create session

                $stripe_checkout_url = $stripeService->createCheckoutSession(
                    array_map(
                        function($item){
                            if ($item['nom'] == 'Adhésion famille') {
                                return [
                                    "productName"=>$item['nom'],
                                    "quantity"=>1,
                                    "amount"=> $item['total'],
                                    "type"=>"",
                                ];
                            }else {
                               return [
                                "productName"=>$item['nom'],
                                "quantity"=>1,
                                "amount"=> $item['total'],
                                "type"=>"",
                               ]; 
                            }
                        },$items)
                );
                
                $session->set('registerContext', $user->getId());
            //?-----------


            //? =======Clean Session

                $session->remove('register1');
                $session->remove('register2');
                $session->remove('register3');
            //?-----------



            //? ======= mail Confirmation
                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('asso100famille@gmail.com', 'Association 100% Famille'))
                        ->to((string) $user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            //?-----------

            // do anything else you need here, like send an email

            // dd($stripe_checkout_url);

                return $this->redirect(
                    $stripe_checkout_url
            );
        }


        return $this->render('registration/confirmation.html.twig', [
            'form' => $form,
            'frais' => $frais,
            'itemsToPaid' => $items,
            'username' => $userName
        ]);
    }
    

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }



    private function cryptRecursively(array $array, int $action = 1): array
    {

        $callback = match ($action) {
            1 => [$this->cryptage, 'encrypt'],
            2 => [$this->cryptage, 'decrypt'],
        };
        // $callback = [$this->cryptage, 'encrypt'];

        // Parcourt chaque élément du tableau
        foreach ($array as $key => $value) {
            // Si l'élément est un tableau, on appelle la fonction récursivement
            if (is_array($value)) {
                $array[$key] = $this->cryptRecursively($value, $action);
            } else {
                // Sinon, on applique la fonction au niveau de l'élément
                $array[$key] = $callback($value);
            }
        }
        return $array;
    }
}
