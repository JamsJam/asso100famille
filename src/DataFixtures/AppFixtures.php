<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Admin;
use App\Entity\Adherent;
use App\Entity\Abonement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $now = new \DateTimeImmutable();

        $admin = (new Admin())
            ->setNom('admin')
            ->setPrenom('admin')
            ->setEmail('admin@admin.fr')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(password_hash('adminadminadmin', PASSWORD_BCRYPT))
        ;
        $manager->persist($admin);
        for ($i = 0; $i < 100; $i++) {
            // Crée un adhérent
            $adherent = new Adherent();
            $adherent->setNom($faker->lastName());
            $adherent->setPrenom($faker->firstName());
            $adherent->setEmail($faker->unique()->safeEmail());
            $adherent->setPassword(password_hash('password', PASSWORD_BCRYPT)); // Mot de passe par défaut
            $adherent->setRoles(['ROLE_USER']);
            $adherent->setVerified(true);
            $adherent->setDdn(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-60 years', '-18 years')));
            $adherent->setProfession($faker->jobTitle());
            $adherent->setAdresse($faker->address());
            $adherent->setAdresse2($faker->optional()->secondaryAddress());
            $adherent->setCodepostal($faker->postcode());
            $adherent->setVille($faker->city());
            $adherent->setTelephone($faker->phoneNumber());

            // Crée un abonnement pour cet adhérent
            $createdAt = $faker->dateTimeBetween('-5 months', 'now');
            $expiredAt = (clone $createdAt)->modify('+1 year');
            
            $abonement = new Abonement();
            $abonement->setCreatedAt(\DateTimeImmutable::createFromMutable($createdAt));
            $abonement->setExpiredAt(\DateTimeImmutable::createFromMutable($expiredAt));
            $abonement->setStatus('active');
            $abonement->setAdherent($adherent);

            // Lie l'abonnement à l'adhérent
            $adherent->setAbonement($abonement);

            // Persiste les entités
            $manager->persist($adherent);
            $manager->persist($abonement);
            
        }

        // Sauvegarde dans la base de données
        $manager->flush();
    }
}
