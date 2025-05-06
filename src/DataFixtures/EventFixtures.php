<?php

namespace App\DataFixtures;

use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use App\Entity\RecurringRule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créez 50 événements ponctuels
        for ($i = 0; $i < 50; $i++) {
            $event = new OneTimeEvent();
            $event->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setStartDate(new \DateTimeImmutable($faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s')))
                ->setEndDate(new \DateTimeImmutable($faker->dateTimeBetween('+1 day', '+2 months')->format('Y-m-d H:i:s')))
                ->setStartHour(new \DateTimeImmutable($faker->time('H:i:s')))
                ->setEndHour(new \DateTimeImmutable($faker->time('H:i:s')))
                ->setIsFree($faker->boolean(70)) // 70% de chances d'être gratuit
                ->setPrice($faker->numberBetween(10, 100))
                ->setUserPrice($faker->boolean(30) ? $faker->numberBetween(5, 50) : null) // 30% de chances d'avoir un prix utilisateur
                ->setImage($faker->imageUrl(640, 480, 'events', true));

            $manager->persist($event);
        }

        // Créez 50 événements récurrents
        for ($i = 0; $i < 50; $i++) {
            $event = new RecurringEvent();
            $event->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setStartDate(new \DateTimeImmutable($faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s')))
                ->setEndDate(new \DateTimeImmutable($faker->dateTimeBetween('+1 day', '+2 months')->format('Y-m-d H:i:s')))
                ->setStartHour(new \DateTimeImmutable($faker->time('H:i:s')))
                ->setEndHour(new \DateTimeImmutable($faker->time('H:i:s')))
                ->setIsFree($faker->boolean(70))
                ->setPrice($faker->numberBetween(10, 100))
                ->setUserPrice($faker->boolean(30) ? $faker->numberBetween(5, 50) : null)
                ->setImage($faker->imageUrl(640, 480, 'events', true));

            // Ajouter une règle de récurrence
            $recurringRule = new RecurringRule();
            $recurringRule->setDaysOfWeek($faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']))
                ->setIsActive(true);

            $event->setRecurringRule($recurringRule);

            $manager->persist($recurringRule);
            $manager->persist($event);
        }

        // Sauvegarder dans la base de données
        $manager->flush();
    }
}
