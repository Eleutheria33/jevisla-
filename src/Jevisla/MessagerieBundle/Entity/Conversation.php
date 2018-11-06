<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\MessagerieBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation.
 *
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="Jevisla\MessagerieBundle\Repository\ConversationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Conversation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="idOne", type="integer")
     */
    private $idOne;

    /**
     * @var int
     *
     * @ORM\Column(name="idTwo", type="integer")
     */
    private $idTwo;

    /**
     * @ORM\OneToMany(targetEntity="Jevisla\MessagerieBundle\Entity\Messages", mappedBy="conversation")
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMessages", type="integer")
     */
    private $nbMessages = 0;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->message = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDate(new \Datetime());
    }

    public function increaseMessage()
    {
        ++$this->nbMessages;
    }

    public function decreaseMessage()
    {
        --$this->nbMessages;
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
     * @return Conversation
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
     * Set idOne.
     *
     * @param int $idOne
     *
     * @return Conversation
     */
    public function setIdOne($idOne)
    {
        $this->idOne = $idOne;

        return $this;
    }

    /**
     * Get idOne.
     *
     * @return int
     */
    public function getIdOne()
    {
        return $this->idOne;
    }

    /**
     * Set idTwo.
     *
     * @param int $idTwo
     *
     * @return Conversation
     */
    public function setIdTwo($idTwo)
    {
        $this->idTwo = $idTwo;

        return $this;
    }

    /**
     * Get idTwo.
     *
     * @return int
     */
    public function getIdTwo()
    {
        return $this->idTwo;
    }

    /**
     * Set messages.
     *
     * @param string $message
     *
     * @return Conversation
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
     * Add message.
     *
     * @param \Jevisla\MessagerieBundle\Entity\Messages $message
     *
     * @return Conversation
     */
    public function addMessage(\Jevisla\MessagerieBundle\Entity\Messages $message)
    {
        $this->message[] = $message;

        return $this;
    }

    /**
     * Remove message.
     *
     * @param \Jevisla\MessagerieBundle\Entity\Messages $message
     */
    public function removeMessage(\Jevisla\MessagerieBundle\Entity\Messages $message)
    {
        $this->message->removeElement($message);
    }

    /**
     * Set nbMessages.
     *
     * @param int $nbMessages
     *
     * @return Conversation
     */
    public function setNbMessages($nbMessages)
    {
        $this->nbMessages = $nbMessages;

        return $this;
    }

    /**
     * Get nbMessages.
     *
     * @return int
     */
    public function getNbMessages()
    {
        return $this->nbMessages;
    }
}
