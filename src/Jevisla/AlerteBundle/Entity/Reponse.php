<?php

namespace Jevisla\AlerteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse.
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="Jevisla\AlerteBundle\Repository\ReponseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Reponse
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
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer", length=15)
     */
    private $authorId;

    /**
     * @var text
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Jevisla\AlerteBundle\Entity\Alerte", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $alerte;

    public function __construct()
    {
        $this->date = new \Datetime();
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getAlerte()->increaseReponse();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getAlerte()->decreaseReponse();
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
     * Set author.
     *
     * @param string $author
     *
     * @return Reponse
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Reponse
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Reponse
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * Set authorId.
     *
     * @param int $authorId
     *
     * @return Reponse
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId.
     *
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }
}
