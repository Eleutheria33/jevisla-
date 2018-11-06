<?php

namespace Jevisla\EvtsCulturelsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commune.
 *
 * @ORM\Table(name="commune")
 * @ORM\Entity(repositoryClass="Jevisla\EvtsCulturelsBundle\Repository\CommuneRepository")
 */
class Commune
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string")
     */
    private $codePostal;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="codeDept", type="string")
     */
    private $codeDept;

    /**
     * @var string
     *
     * @ORM\Column(name="nomDept", type="string", length=50)
     */
    private $nomDept;

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
     * @return Commune
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
     * Set code.
     *
     * @param int $code
     *
     * @return Commune
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set codePostal.
     *
     * @param int $codePostal
     *
     * @return Commune
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
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return Commune
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
     * @return Commune
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
     * Set codeDept.
     *
     * @param int $codeDept
     *
     * @return Commune
     */
    public function setCodeDept($codeDept)
    {
        $this->codeDept = $codeDept;

        return $this;
    }

    /**
     * Get codeDept.
     *
     * @return int
     */
    public function getCodeDept()
    {
        return $this->codeDept;
    }

    /**
     * Set nomDept.
     *
     * @param string $nomDept
     *
     * @return Commune
     */
    public function setNomDept($nomDept)
    {
        $this->nomDept = $nomDept;

        return $this;
    }

    /**
     * Get nomDept.
     *
     * @return string
     */
    public function getNomDept()
    {
        return $this->nomDept;
    }
}
