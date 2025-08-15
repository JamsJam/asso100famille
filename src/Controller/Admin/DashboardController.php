<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Repository\AbonementRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\AdherentRepository;
use App\Repository\ReservationRepository;
use App\Service\ThemeService;
use App\Service\TranslateDateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(
        AdherentRepository $ar,
        ReservationRepository $rr,
        ChartBuilderInterface $chartBuilder,
        TranslateDateService $translate_date_service,
        ThemeService $theme_service
    ): Response {

        $theme = $theme_service->getTheme();

        if ($this->isGranted('ROLE_ADMIN')) {

            $adherents = $ar->findAll();
            $countAdherent = count($adherents);


            // !  =========== Chart
            $today = new \DateTimeImmutable();
            $activAdhrentsData = [];
            $refMonth = [];

            for ($index = 0; $index  < 7; $index++) {
                $monthDelay = (6 - $index) + 7;

                $dateReference = new \DateTimeImmutable($today->modify("-" . $monthDelay . " month")->format('Y-m-t'));
                $refMonth[] = $translate_date_service->getFrenchMonth($dateReference->format('m') - 1) . " " . $dateReference->format('Y');

                $activAdhrents = array_filter($adherents, function (Adherent $adherent) use ($dateReference) {
                    if ($adherent->getAbonement()->getStatus() == "active" && $adherent->getAbonement()->getCreatedAt() < $dateReference && $adherent->getAbonement()->getExpiredAt() > $dateReference) {
                        # code...
                        return $adherent;
                    }
                });
                $activAdhrentsData[] = count($activAdhrents);

            }
            // dump($dateReference, $refMonth,$activAdhrents);
            $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
            $chart->setData([
                'labels' => $refMonth,
                'datasets' => [
                    [
                        'label' => 'Adhérents',
                        'backgroundColor' => 'rgba(90, 35, 47, 1)',
                        'borderColor' => 'rgba(90, 35, 47, 1)',
                        // "pointBackgroundColor"=> 'rgba(90, 35, 47, 1)',
                        // "pointBorderColor"=> 'rgba(90, 35, 47, 1)',
                        "pointBackgroundColor" => 'hsla(272, 72%, 59%, 1.00)',
                        "pointBorderColor" => 'hsla(272, 72%, 59%, 1.00)',
                        'data' => $activAdhrentsData,
                    ],
                ],
            ]);
            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 100,
                    ],
                ],
            ]);

            return $this->render('admin/dashboard/index.html.twig', [
                'countAdherent' => $countAdherent,
                'chart' => $chart,
                "theme"=>$theme,

            ]);
        }

        $user = $this->getUser();
        //! === reservation
        $reservations = $rr->findBy(['adherent' => $user],);
        $recurringReservation = [];
        $ponctualReservation = [];
        $cache = [];


        foreach ($reservations as $reservation) {
            # code...
            if ($reservation->getTypeEvent() === "recurring") {

                if (!in_array($reservation->getREvent()->getId(), $cache)) {
                    $cache[] = $reservation->getREvent()->getId();
                    $recurringReservation[] = $reservation;
                }
            } elseif ($reservation->getTypeEvent() === "ponctuel") {

                $ponctualReservation[] = $reservation;
            }
        }

        //! ==== abonnement
        $abonement = $user->getAbonement();

        return $this->render('admin/dashboard/index.html.twig', [

            "pEvents" => $ponctualReservation,
            "rEvents" => $recurringReservation,
            'dayMapping' => $translate_date_service->getFrenchDaytable(),
            "abonement" => $abonement,
            "theme"=>$theme,

        ]);
    }
}
