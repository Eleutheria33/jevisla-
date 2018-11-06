<?php

// src/Jevisla/GeneralBundle/Beta/BetaHTMLAdder.php

namespace Jevisla\GeneralBundle\Beta;

use Symfony\Component\HttpFoundation\Response;

class BetaHTMLAdder
{
    // Méthode pour ajouter le « bêta » à une réponse
    public function addBeta(Response $response, $remainingDays)
    {
        $content = $response->getContent();
        //(CSS en ligne, mais il faudrait utiliser un fichier CSS !)
        $html = '<div style="position: absolute; font-weight: bolder; top: 0; background: orange; width: 100%; text-align: center; padding: 1.2em;">Version Beta, lancement J-'.(int) $remainingDays.' !</div>';
        // Insertion du code dans la page, au début du <body>
        $content = str_replace(
            '<body>',
            '<body> '.$html,
            $content
        );
        // Modification du contenu dans la réponse
        $response->setContent($content);

        return $response;
    }
}
