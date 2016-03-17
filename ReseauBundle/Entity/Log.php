<?php
/**
 * Created by PhpStorm.
 * User: adin
 * Date: 08/02/2016
 * Time: 18:57
 */

namespace Domotique\ReseauBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Log
 *
 * @ORM\Table(name="domotique__sensor_log")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Domotique\ReseauBundle\Repository\LogRepository")*
 */
class Log
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
     * @ORM\Column(name="sonsor_value", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $sonsorValue;
    /**
     * @var integer
     *
     * @ORM\Column(name="sonsor_id", type="integer", nullable=true)
     */
    private $sensorId;
    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

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
     * @var \Domotique\ReseauBundle\Entity\SensorType
     *
     * @ORM\ManyToOne(targetEntity="Domotique\ReseauBundle\Entity\SensorType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sensor_type", referencedColumnName="id")
     * })
     */
    private $sensorType;
    /**
     * @var \Domotique\ReseauBundle\Entity\SensorUnit
     *
     * @ORM\ManyToOne(targetEntity="Domotique\ReseauBundle\Entity\SensorUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sonsor_unit", referencedColumnName="id")
     * })
     */
    private $sensorUnit;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return SondeUnit
     */
    public function getSensorUnit()
    {
        return $this->sensorUnit;
    }

    /**
     * @param SondeUnit $sensorUnit
     */
    public function setSensorUnit($sensorUnit)
    {
        $this->sensorUnit = $sensorUnit;
    }

    public function __toString()
    {
        return ' > '.$this->sensorValue;
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
    public function getSonsorValue()
    {
        return $this->sonsorValue;
    }

    /**
     * @param string $sonsorValue
     */
    public function setSonsorValue($sonsorValue)
    {
        $this->sonsorValue = $sonsorValue;
    }

    /**
     * @return int
     */
    public function getSensorId()
    {
        return $this->sensorId;
    }

    /**
     * @param int $sensorId
     */
    public function setSensorId($sensorId)
    {
        $this->sensorId = $sensorId;
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
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return SondeType
     */
    public function getSensorType()
    {
        return $this->sensorType;
    }

    /**
     * @param SondeType $sensorType
     */
    public function setSensorType($sensorType)
    {
        $this->sensorType = $sensorType;
    }
}
