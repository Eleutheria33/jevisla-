<?php

namespace Jevisla\GeneralBundle\TextTraitement;

class TextTroncation
{
    public function getTextTronqué($text, $long, $limit)
    {
        // on tronque les textes
        $textTrq = explode(' ', $text);
        if (count($textTrq) > $long) {
            $textTrq = implode(' ', array_slice($textTrq, 0, $limit)).'...';
        }

        return $textTrq;
    }
}
