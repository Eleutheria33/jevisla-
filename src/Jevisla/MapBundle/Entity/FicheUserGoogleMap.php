<?php

namespace Jevisla\MapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheUserGoogleMap.
 *
 * @ORM\Table(name="fiche_user_google_map")
 * @ORM\Entity(repositoryClass="Jevisla\MapBundle\Repository\FicheUserGoogleMapRepository")
 * @ORM\HasLifecycleCallbacks
 */
class FicheUserGoogleMap
{
    /**
     * @var int
     *
     * @ORM\Column(name="id",               type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", unique=true)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo1", type="string", length=30, nullable=true)
     */
    private $pseudo1;

    /**
     * @var string
     *
     * @ORM\Column(name="devise", type="string", length=255, nullable=true)
     */
    private $devise;

    /**
     * @ORM\OneToOne(targetEntity="Jevisla\MapBundle\Entity\Avatar", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
     */
    private $adresse;

    /**
     * @var bool
     *
     * @ORM\Column(name="vue", type="boolean", nullable=true)
     */
    private $vue;

    /**
     * @var text
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    private $texte;

    /**
     * @var int
     *
     * @ORM\Column(name="zoom", type="integer", nullable=true)
     * @Assert\Range(
     *     min=6,
     *     max=20,
     *     minMessage="Votre zoom doit être d'au moins {{ limit }} ",
     *     maxMessage="Votre zoom ne doit pas excéder {{ limit }}"
     * )
     */
    private $zoom;

    /**
     * @var string
     *
     * @ORM\Column(name="icone", type="string", length=255, nullable=true)
     */
    private $icone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=50, nullable=true)
     *
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email.",
     *     checkMX=true
     * )
     */
    private $mail;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="integer", length=15, nullable=true)
     */
    private $phone;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUser.
     *
     * @param int $idUser
     *
     * @return FicheUserGoogleMap
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser.
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set pseudo1.
     *
     * @param string $pseudo1
     *
     * @return FicheUserGoogleMap
     */
    public function setPseudo1($pseudo1)
    {
        $this->pseudo1 = $pseudo1;

        return $this;
    }

    /**
     * Get pseudo1.
     *
     * @return string
     */
    public function getPseudo1()
    {
        return $this->pseudo1;
    }

    /**
     * Set devise.
     *
     * @param string $devise
     *
     * @return FicheUserGoogleMap
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise.
     *
     * @return string
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set FicheUserGoogleMap.
     *
     * @param \Jevisla\MapBundle\Entity\Avatar $avatar
     *
     * @return FicheUserGoogleMap
     */
    public function setAvatar(Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return FicheUserGoogleMap
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set vue.
     *
     * @param bool $vue
     *
     * @return FicheUserGoogleMap
     */
    public function setVue($vue)
    {
        $this->vue = $vue;

        return $this;
    }

    /**
     * Get vue.
     *
     * @return bool
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set texte.
     *
     * @param text $texte
     *
     * @return FicheUserGoogleMap
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte.
     *
     * @return text
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set zoom.
     *
     * @param int $zoom
     *
     * @return FicheUserGoogleMap
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom.
     *
     * @return int
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * Set icone.
     *
     * @param string $icone
     *
     * @return FicheUserGoogleMap
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get icone.
     *
     * @return string
     */
    public function getIcone()
    {
        return $this->icone;
    }

    /**
     * Set mail.
     *
     * @param string $mail
     *
     * @return FicheUserGoogleMap
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set phone.
     *
     * @param int $phone
     *
     * @return FicheUserGoogleMap
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
