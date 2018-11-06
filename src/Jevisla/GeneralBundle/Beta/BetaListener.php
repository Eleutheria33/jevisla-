<?php

// src/Jevisla/GeneralBundle/Beta/BetaListener.php

namespace Jevisla\GeneralBundle\Beta;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
    // Notre processeur
    protected $betaHTML;

    // La date de fin de la version bêta :

    protected $endDate;

    public function __construct(BetaHTMLAdder $betaHTML, $endDate)
    {
        $this->betaHTML = $betaHTML;
        $this->endDate = new \Datetime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {
        // On teste si la requête est bien la requête principale (et non une sous-requête)
        if (!$event->isMasterRequest()) {
            return;
        }
        $remainingDays = $this->endDate->diff(new \Datetime())->days;

        if ($remainingDays <= 0) {
            // Si la date est dépassée, on ne fait rien
            return;
        }

        // On récupère la réponse que le gestionnaire a insérée dans l'évènement
        $response = $this->betaHTML->addBeta($event->getResponse(), $remainingDays);

        // Ici on modifie comme on veut la réponse…

        // Puis on insère la réponse modifiée dans l'évènement
        $event->setResponse($response);
    }
}
