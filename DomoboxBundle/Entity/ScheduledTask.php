<?php

namespace Domotique\DomoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * ScheduledTask
 *
 * @ORM\Table(name="domotique__task_scheduled")
 * @ORM\Entity
 */
class ScheduledTask
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;
    /**
     * @var datetime $start
     *
     * @ORM\Column(name="start", type="datetime", nullable=false)
     */
    private $start;
    /**
     * @var datetime $stop
     *
     * @ORM\Column(name="stop", type="datetime", nullable=false)
     */
    private $stop;
    /**
     * @var \Domotique\ReseauBundle\Entity\Module
     *
     * @ORM\ManyToOne(targetEntity="Domotique\ReseauBundle\Entity\Module")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     * })
     */
    private $module;
    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=100, nullable=true)
     */
    private $action;
    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=100, nullable=true)
     */
    private $valeur;
    /**
     * @var \AppBundle\BackBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\BackBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;
    /**
     * @var boolean
     *
     * @ORM\Column(name="isRun", type="boolean", nullable=false)
     */
    private $run;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->start = new \DateTime();
        $this->stop = new \DateTime();
        $this->run = false;
    }

    /**
     * @return boolean
     */
    public function isRun()
    {
        return $this->run;
    }

    /**
     * @param boolean $run
     */
    public function setRun($run)
    {
        $this->run = $run;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return datetime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param datetime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return datetime
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @param datetime $stop
     */
    public function setStop($stop)
    {
        $this->stop = $stop;
    }

    /**
     * @return \Domotique\ReseauBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param \Domotique\ReseauBundle\Entity\Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param string $valeur
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    }

    /**
     * @return \AppBundle\BackBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \AppBundle\BackBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        return $this->action;
    }


}

