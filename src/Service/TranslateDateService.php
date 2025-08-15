<?php
namespace App\Service;

final class TranslateDateService 
{
    public function getFrenchMonth(int $monthNumber){

        $frenchMonth = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

        return $frenchMonth[$monthNumber];
    }
    public function getFrenchDay(string $day){

            $daysMapping = [
                'Monday'    => 'Lundi',
                'Tuesday'   => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday'  => 'Jeudi',
                'Friday'    => 'Vendredi',
                'Saturday'  => 'Samedi',
                'Sunday'    => 'Dimanche'
            ];
        return $daysMapping[$day];
    }
    public function getFrenchDaytable(){

            $daysMapping = [
                'Monday'    => 'Lundi',
                'Tuesday'   => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday'  => 'Jeudi',
                'Friday'    => 'Vendredi',
                'Saturday'  => 'Samedi',
                'Sunday'    => 'Dimanche'
            ];
        return $daysMapping;
    }


}
