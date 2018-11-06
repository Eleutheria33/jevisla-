<?php

namespace Jevisla\AlerteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlerteSkill.
 *
 * @ORM\Table(name="alerte_skill")
 * @ORM\Entity(repositoryClass="Jevisla\AlerteBundle\Repository\AlerteSkillRepository")
 */
class AlerteSkill
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
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="Jevisla\AlerteBundle\Entity\Alerte")
     * @ORM\JoinColumn(nullable=false)
     */
    private $alerte;

    /**
     * @ORM\ManyToOne(targetEntity="Jevisla\AlerteBundle\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;

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
     * Set level.
     *
     * @param string $level
     *
     * @return AlerteSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param Alerte $alerte
     */
    public function setAlerte(Alerte $alerte)
    {
        $this->alerte = $alerte;
    }

    /**
     * @return Alerte
     */
    public function getAlerte()
    {
        return $this->alerte;
    }

    /**
     * @param Skill $skill
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;
    }

    /**
     * @return Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
