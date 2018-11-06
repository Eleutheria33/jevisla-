<?php

namespace Jevisla\GeneralBundle\Twig;

use Jevisla\GeneralBundle\PseudoUser\PseudoUser;

class PseudoUserExtension extends \Twig_Extension
{
    /**
     * @var JevislaPseudoUser
     */
    private $PseudoUser;

    public function __construct(PseudoUser $PseudoUser)
    {
        $this->PseudoUser = $PseudoUser;
    }

    public function checkIfArgumentIsId($id)
    {
        return $this->PseudoUser->getPseudo($id);
    }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getObjetPseudoUser', array($this, 'checkIfArgumentIsId')),
        );
    }

    // La méthode getName() identifie l'extension Twig, elle est obligatoire
    public function getName()
    {
        return 'PseudoUser';
    }
}
