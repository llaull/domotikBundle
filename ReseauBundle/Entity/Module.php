<?php

namespace Domotique\ReseauBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="domotique__module")
 * @ORM\Entity
 */
class Module
{
    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    private $created;
    /**
     * @var datetime $modified
     *
     * @ORM\Column(type="datetime")
     */
    private $modified;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="mac", type="string", length=17, unique=true, nullable=false)
     */
    private $adressMac;
    /**
     * @var string
     *
     * @ORM\Column(name="ipv4", type="string", length=15, nullable=false)
     */
    private $adressIpv4;
    /**
     * @var \Domotique\ReseauBundle\Entity\Emplacement
     *
     * @ORM\ManyToOne(targetEntity="Domotique\ReseauBundle\Entity\Emplacement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emplacement_id", referencedColumnName="id")
     * })
     */
    private $location;

    public function __construct()
    {
        $this->modified = new \DateTime();
        $this->created = new \DateTime();
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
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param datetime $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAdressMac()
    {
        return $this->adressMac;
    }

    /**
     * @param string $adressMac
     */
    public function setAdressMac($adressMac)
    {
        $this->adressMac = $adressMac;
    }

    /**
     * @return string
     */
    public function getAdressIpv4()
    {
        return $this->adressIpv4;
    }

    /**
     * @param string $adressIpv4
     */
    public function setAdressIpv4($adressIpv4)
    {
        $this->adressIpv4 = $adressIpv4;
    }

    /**
     * @return Emplacement
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Emplacement $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}
