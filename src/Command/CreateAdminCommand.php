<?php

namespace App\Command;


use App\Entity\Admin;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:create-Admin',
    description: 'Creates a new admin.',
    hidden: false,
    aliases: ['app:a-admin']
)]
class CreateAdminCommand extends Command
{


    public function __construct
    (
        public MailerService $mailerService,
        public UrlGeneratorInterface $urlGeneratorInterface,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    )
    {

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('prenom', InputArgument::OPTIONAL, 'The username of the user.')
            ->addArgument('nom', InputArgument::OPTIONAL, 'The username of the user.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The username of the user.')
            ->addArgument('password',  InputArgument::OPTIONAL , 'User password')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

                $io = new SymfonyStyle($input, $output);

        // Afficher le titre de la commande
        $output->writeln([
            '  Amind Creator',
            '===================',
            '',
        ]);

        // Introduction
        $output->writeln(['', '', '']);

        // Poser les questions à l'utilisateur avec un affichage personnalisé
        $output->writeln([
            '=========================',
            'What\'s the admin\'s last name?',
            '=========================',
        ]);
        $nom = $io->ask('Enter the last name');

        $output->writeln([
            '============================',
            'What\'s the admin\'s first name?',
            '============================',
        ]);
        $prenom = $io->ask('Enter the first name');

        $output->writeln([
            '=========================',
            'What\'s the admin\'s email?',
            '=========================',
        ]);
        $email = $io->ask('Enter the email address');

        $output->writeln([
            '============================',
            'What\'s the admin\'s password?',
            '============================',
        ]);
        $password = $io->askHidden('Enter the password (input will be hidden)');

        // Résumé des informations collectées
        $output->writeln([
            '',
            '=========================',
            'Summary of admin Information',
            '=========================',
        ]);
        $output->writeln('Last Name: ' . $nom);
        $output->writeln('First Name: ' . $prenom);
        $output->writeln('Email: ' . $email);
        $output->writeln('Password: (hidden for security)');

        // Appeler le service pour créer l'utilisateur
        // $this->userManager->createUser($prenom, $nom, $email, $password);

        // Afficher un message de succès








        $progressBar = new ProgressBar($output, 50);
        $progressBar->setMessage('Start');
        $progressBar->display();
        $progressBar->start();
        sleep(2);
        $i = 0;
            $progressBar->setMessage('Task is in progress...');
            $progressBar->display();
        while ($i++ < 10) {

            $progressBar->advance();
            usleep(100000);
        }
            $progressBar->setMessage('Create passport...');
            $progressBar->display();
        while ($i++ < 30) {

            $progressBar->advance();
            usleep(100000);
        }
            $progressBar->setMessage('jumping firewall...');
            $progressBar->display();
        while ($i++ < 50) {

            $progressBar->advance();
            usleep(100000);
        }
        $progressBar->setMessage('END');
        $progressBar->display();
        $progressBar->finish();

            try {

                ($admin = new Admin())
                    ->setNom($nom)
                    ->setPrenom($prenom)
                    ->setEmail($email)
                    ->setPassword($this->passwordHasher->hashPassword($admin, $password))
                    ->setRoles(["ROLE_ADMIN"])
                ;

                $this->entityManager->persist($admin);
                $this->entityManager->flush();


                $this->mailerService->sendTemplatedMail(
                    // "j.antoine971@hotmail.fr", //! test
                    $email, //! prod
                    "contact@tiers-lieu100p100famille.fr",
                    "Vos identifiants",
                    "email/admin.html.twig",
                    [
                        'firstname' => $prenom,
                        'lastname' => $nom,
                        'emaill' => $email,
                        'temporary_password' => $password,
                        'login_url' => $this->urlGeneratorInterface->generate("app_login",[], UrlGeneratorInterface::ABSOLUTE_PATH),
                    ]
                    );
            } catch (\Throwable $th) {
                $output->writeln([
                    '',
                    '-------------------------',
                    '    Admin CREATE FAIL!',
                    $th->getMessage(),
                    '=========================',
                ]);
                
                return Command::FAILURE;
            }



        $output->writeln([
            '',
            '=========================',
            'Admin Successfully Created!',
            '=========================',
        ]);

        return Command::SUCCESS;

    }


}