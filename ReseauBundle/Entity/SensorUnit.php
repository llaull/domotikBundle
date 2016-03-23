<?php

namespace Domotique\ReseauBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SondeUnit
 *
 * @ORM\Table(name="domotique__sensor_unit")
 * @ORM\Entity
 */
class SensorUnit
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
     * @var string
     *
     * @ORM\Column(name="symbole", type="string", length=90, nullable=false)
     */
    private $symbole;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;


    public function __toString()
    {
        return $this->name;
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
    public function getSymbole()
    {
        return $this->symbole;
    }

    /**
     * @param string $symbole
     */
    public function setSymbole($symbole)
    {
        $this->symbole = $symbole;
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


}
