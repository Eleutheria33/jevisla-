<?php

// src/UserBundle/Entity/User.php

namespace Jevisla\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Jevisla\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id",               type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=25, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=25, nullable=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="portable", type="string", length=15, nullable=true)
     */
    private $portable;

    /**
     * @var int
     *
     * @ORM\Column(name="numeroAd", type="integer", length=25, nullable=true)
     */
    private $numeroAd;

    /**
     * @var string
     *
     * @ORM\Column(name="voieAd", type="string", length=25, nullable=true)
     */
    private $voieAd;

    /**
     * @var string
     *
     * @ORM\Column(name="nomVoieAd", type="string", length=25, nullable=true)
     */
    private $nomVoieAd;

    /**
     * @var string
     *
     * @ORM\Column(name="villeAd", type="string", length=50, nullable=true)
     */
    private $villeAd;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer", length=10, nullable=true)
     */
    private $codePostal;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", length=25, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", length=25, nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="coefZoneContact", type="float", length=25, nullable=true)
     */
    private $coefZoneContact;

    /**
     * @var float
     *
     * @ORM\Column(name="latitudeNE", type="float", length=25, nullable=true)
     */
    private $latitudeNE;

    /**
     * @var float
     *
     * @ORM\Column(name="latitudeSW", type="float", length=25, nullable=true)
     */
    private $latitudeSW;

    /**
     * @var float
     *
     * @ORM\Column(name="longitudeNE", type="float", length=25, nullable=true)
     */
    private $longitudeNE;

    /**
     * @var float
     *
     * @ORM\Column(name="longitudeSW", type="float", length=25, nullable=true)
     */
    private $longitudeSW;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=15, nullable=true)
     */
    private $civilite;

    /**
     * @ORM\OneToOne(targetEntity="Jevisla\UserBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Jevisla\AlerteBundle\Entity\Alerte", mappedBy="user")
     */
    private $alertes;

    /**
     * @var int
     *
     * @ORM\Column(name="nbAlertes", type="integer")
     */
    private $nbAlertes = 0;

    /**
     * @ORM\OneToOne(targetEntity="Jevisla\MapBundle\Entity\FicheUserGoogleMap", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="fiche_user_google_map")
     */
    private $ficheGoogle;

    public function __construct()
    {
        $this->alertes = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }

    /**
     * @ORM\PreUpdate
     */
    public function increaseAlerte()
    {
        ++$this->nbAlertes;
    }

    public function decreaseAlerte()
    {
        --$this->nbAlertes;
    }

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
     * Set nom.
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set pseudo.
     *
     * @param string $pseudo
     *
     * @return User
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo.
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set portable.
     *
     * @param string $portable
     *
     * @return User
     */
    public function setPortable($portable)
    {
        $this->portable = $portable;

        return $this;
    }

    /**
     * Get portable.
     *
     * @return string
     */
    public function getPortable()
    {
        return $this->portable;
    }

    /**
     * Set numeroAd.
     *
     * @param int $numeroAd
     *
     * @return User
     */
    public function setNumeroAd($numeroAd)
    {
        $this->numeroAd = $numeroAd;

        return $this;
    }

    /**
     * Get numeroAd.
     *
     * @return int
     */
    public function getNumeroAd()
    {
        return $this->numeroAd;
    }

    /**
     * Set voieAd.
     *
     * @param string $voieAd
     *
     * @return User
     */
    public function setVoieAd($voieAd)
    {
        $this->voieAd = $voieAd;

        return $this;
    }

    /**
     * Get voieAd.
     *
     * @return string
     */
    public function getVoieAd()
    {
        return $this->voieAd;
    }

    /**
     * Set villeAd.
     *
     * @param string $villeAd
     *
     * @return User
     */
    public function setVilleAd($villeAd)
    {
        $this->villeAd = $villeAd;

        return $this;
    }

    /**
     * Get villeAd.
     *
     * @return string
     */
    public function getVilleAd()
    {
        return $this->villeAd;
    }

    /**
     * Set codePostal.
     *
     * @param int $codePostal
     *
     * @return User
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal.
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set civilite.
     *
     * @param string $civilite
     *
     * @return User
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite.
     *
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set nomVoieAd.
     *
     * @param string $nomVoieAd
     *
     * @return User
     */
    public function setNomVoieAd($nomVoieAd)
    {
        $this->nomVoieAd = $nomVoieAd;

        return $this;
    }

    /**
     * Get nomVoieAd.
     *
     * @return string
     */
    public function getNomVoieAd()
    {
        return $this->nomVoieAd;
    }

    /**
     * Set image.
     *
     * @param \Jevisla\UserBundle\Entity\Image $image
     *
     * @return User
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \Jevisla\UserBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set coefZoneContact.
     *
     * @param float $coefZoneContact
     *
     * @return User
     */
    public function setCoefZoneContact($coefZoneContact)
    {
        $this->coefZoneContact = $coefZoneContact;

        return $this;
    }

    /**
     * Get coefZoneContact.
     *
     * @return float
     */
    public function getCoefZoneContact()
    {
        return $this->coefZoneContact;
    }

    /**
     * Set latitudeNE.
     *
     * @param float $latitudeNE
     *
     * @return User
     */
    public function setLatitudeNE($latitudeNE)
    {
        $this->latitudeNE = $latitudeNE;

        return $this;
    }

    /**
     * Get latitudeNE.
     *
     * @return float
     */
    public function getLatitudeNE()
    {
        return $this->latitudeNE;
    }

    /**
     * Set latitudeSW.
     *
     * @param float $latitudeSW
     *
     * @return User
     */
    public function setLatitudeSW($latitudeSW)
    {
        $this->latitudeSW = $latitudeSW;

        return $this;
    }

    /**
     * Get latitudeSW.
     *
     * @return float
     */
    public function getLatitudeSW()
    {
        return $this->latitudeSW;
    }

    /**
     * Set longitudeNE.
     *
     * @param float $longitudeNE
     *
     * @return User
     */
    public function setLongitudeNE($longitudeNE)
    {
        $this->longitudeNE = $longitudeNE;

        return $this;
    }

    /**
     * Get longitudeNE.
     *
     * @return float
     */
    public function getLongitudeNE()
    {
        return $this->longitudeNE;
    }

    /**
     * Set longitudeSW.
     *
     * @param float $longitudeSW
     *
     * @return User
     */
    public function setLongitudeSW($longitudeSW)
    {
        $this->longitudeSW = $longitudeSW;

        return $this;
    }

    /**
     * Get longitudeSW.
     *
     * @return float
     */
    public function getLongitudeSW()
    {
        return $this->longitudeSW;
    }

    public function setFicheGoogle($ficheGoogle = null)
    {
        $this->ficheGoogle = $ficheGoogle;
    }

    public function getFicheGoogle()
    {
        return $this->ficheGoogle;
    }

    /**
     * Add alerte.
     *
     * @param \Jevisla\AlerteBundle\Entity\Alerte $alerte
     *
     * @return User
     */
    public function addAlerte(\Jevisla\AlerteBundle\Entity\Alerte $alerte)
    {
        $this->alertes[] = $alerte;

        return $this;
    }

    /**
     * Remove alerte.
     *
     * @param \Jevisla\AlerteBundle\Entity\Alerte $alerte
     */
    public function removeAlerte(\Jevisla\AlerteBundle\Entity\Alerte $alerte)
    {
        $this->alertes->removeElement($alerte);
    }

    /**
     * Get alertes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlertes()
    {
        return $this->alertes;
    }

    /**
     * Set nbReponses.
     *
     * @param int $nbAlertes
     *
     * @return Alerte
     */
    public function setNbAlertes($nbAlertes)
    {
        $this->nbAlertes = $nbAlertes;

        return $this;
    }

    /**
     * Get nbAlertes.
     *
     * @return int
     */
    public function getNbAlertes()
    {
        return $this->nbAlertes;
    }
}
