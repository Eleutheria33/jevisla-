<?php

namespace Jevisla\GeneralBundle\RegexDate;

class RegexDate
{
    /**
     * @return RegexDate
     */
    public function getNewDate($date, $order)
    {
        // on transforme en format d/m/y
        switch ($order) {
        case 'ymd':
            $madate = preg_replace('#^([0-9]{4})-?/?([0-9]{2})-?/?([0-9]{2})#', ' Le $3 / $2 / $1', $date);
            break;
        case 'mdy':
            $madate = preg_replace('#^([0-9]{2})-?/?([0-9]{2})-?/?([0-9]{4})#', ' Le $2 / $1 / $3', $date);
            break;
        case 'dym':
            $madate = preg_replace('#^([0-9]{2})-?/?([0-9]{4})-?/?([0-9]{2})#', ' Le $1 / $3 / $2', $date);
            break;
        }

        return $madate;
    }
}
