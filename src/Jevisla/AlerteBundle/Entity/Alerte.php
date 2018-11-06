<?php
/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */

namespace Jevisla\AlerteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 *
 * Alerte.
 *
 * @ORM\Table(name="alerte")
 * @ORM\Entity(repositoryClass="Jevisla\AlerteBundle\Repository\AlerteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Alerte
{
    /**
     * Identifiant.
     *
     * @var int
     *
     * @ORM\Column(name="id",               type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Date création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Titre de l'alerte.
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * Auteur de l'alerte.
     *
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * Identifiant de l'auteur.
     *
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer", length=15)
     */
    private $authorId;

    /**
     * Contenu de l'alerte.
     *
     * @var text
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * Flag de publication.
     *
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * Date de modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Lien pour l'image de alerte avec ImageAlerte.
     *
     * @ORM\ManyToOne(targetEntity="Jevisla\AlerteBundle\Entity\ImageAlerte",
     * cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $imageAlerte;

    /**
     * Lien pour les catégories.
     *
     * @ORM\ManyToMany(targetEntity="Jevisla\AlerteBundle\Entity\Category",
     * cascade={"persist", "remove"})
     * @ORM\JoinTable(name="alerte_category")
     */
    private $categories;

    /**
     * Nombre total de réponses.
     *
     * @var int
     *
     * @ORM\Column(name="nbReponses", type="integer")
     */
    private $nbReponses = 0;

    /**
     * Nom réduit de l'adresse de l'alerte.
     *
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug",      type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * Lien des réponses de l'alerte avec l'entity Reponse.
     *
     * @ORM\OneToMany(targetEntity="Jevisla\AlerteBundle\Entity\Reponse",
     * mappedBy="alerte")
     */
    private $reponses;

    /**
     * Lien avec l'entity User.
     *
     * @ORM\ManyToOne(targetEntity="Jevisla\UserBundle\Entity\User",
     * inversedBy="alertes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * Instanciation de la classe Alerte, init : date, categories & reponses.
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->categories = new ArrayCollection();
        $this->reponses = new ArrayCollection();
    }

    /**
     * Mise à jour de Update en PreUpdate.
     *
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    public function increaseReponse()
    {
        ++$this->nbReponses;
    }

    public function decreaseReponse()
    {
        --$this->nbReponses;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getUser()->increaseAlerte();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getUser()->decreaseAlerte();
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Alerte
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
     * Set title.
     *
     * @param string $title
     *
     * @return Alerte
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Alerte
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
     * @return Alerte
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
     * Set published.
     *
     * @param bool $published
     *
     * @return Alerte
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published.
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Alerte
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set nbReponses.
     *
     * @param int $nbReponses
     *
     * @return Alerte
     */
    public function setNbReponses($nbReponses)
    {
        $this->nbReponses = $nbReponses;

        return $this;
    }

    /**
     * Get nbReponses.
     *
     * @return int
     */
    public function getNbReponses()
    {
        return $this->nbReponses;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setImageAlerte(ImageAlerte $imageAlerte = null)
    {
        $this->imageAlerte = $imageAlerte;
    }

    public function getImageAlerte()
    {
        return $this->imageAlerte;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Reponse $reponse
     */
    public function addReponse(Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        // On lie l'annonce à la candidature
        $reponse->setAlerte($this);
    }

    /**
     * @param Reponse $reponse
     */
    public function removeReponse(Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }

    /**
     * Get reponses.
     *
     * @return string
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * Set authorId.
     *
     * @param int $authorId
     *
     * @return Alerte
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

    /**
     * Set user.
     *
     * @param \Jevisla\UserBundle\Entity\User $user
     *
     * @return Alerte
     */
    public function setUser(\Jevisla\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Jevisla\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
