<?php

namespace Jevisla\MessagerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages.
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="Jevisla\MessagerieBundle\Repository\MessagesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Messages
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
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="idConversation", type="integer")
     */
    private $idConversation;

    /**
     * @var text
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var bool
     *
     * @ORM\Column(name="lu", type="boolean", nullable=true)
     */
    private $lu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="Jevisla\MessagerieBundle\Entity\Conversation", inversedBy="message")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->lu = false;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getConversation()->increaseMessage();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getConversation()->decreaseMessage();
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
     * Set number.
     *
     * @param int $number
     *
     * @return Messages
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set idUser.
     *
     * @param int $idUser
     *
     * @return Messages
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
     * Set message.
     *
     * @param string $message
     *
     * @return Messages
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set lu.
     *
     * @param bool $lu
     *
     * @return Messages
     */
    public function setLu($lu)
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get lu.
     *
     * @return bool
     */
    public function getLu()
    {
        return $this->lu;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Messages
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation.
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set conversation.
     *
     * @param \Jevisla\MessagerieBundle\Entity\Conversation $conversation
     *
     * @return Messages
     */
    public function setConversation(\Jevisla\MessagerieBundle\Entity\Conversation $conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation.
     *
     * @return \Jevisla\MessagerieBundle\Entity\Conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set idConversation.
     *
     * @param int $idConversation
     *
     * @return Messages
     */
    public function setIdConversation($idConversation)
    {
        $this->idConversation = $idConversation;

        return $this;
    }

    /**
     * Get idConversation.
     *
     * @return int
     */
    public function getIdConversation()
    {
        return $this->idConversation;
    }
}
