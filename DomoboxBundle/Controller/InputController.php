<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domotique\ReseauBundle\Entity\Log;

class InputController extends Controller
{


    /**
     * @return JsonResponse
     * $sensorFluxAdd = new \DateTime(rand(-12, 12).' hour');
     */
    public function addFuxFakeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sensorFluxAdd = new \DateTime();

        $moduleId = 1;
        $sensorId = 1;
        $sensorTypeId = 8;
        $sensorUnitId = 1;
        $sensorValue = rand(20, 25);

        $log = new Log();

        $moduleX = $em->getRepository('DomotiqueReseauBundle:Module')->find($moduleId);
        $sensorType = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($sensorTypeId);
        $sensorUnit = $em->getRepository('DomotiqueReseauBundle:SensorUnit')->find($sensorUnitId);

        try {
            $log->setModule($moduleX);
            $log->setSensorId($sensorId);
            $log->setSensorType($sensorType);
            $log->setSensorUnit($sensorUnit);
            $log->setSonsorValue($sensorValue);
            $log->setCreated($sensorFluxAdd);
            $em->persist($log);
            $em->flush();
        } catch (\Doctrine\ORM\EntityNotFoundException $e) {
            error_log($e->getMessage());
            return new JsonResponse(array('requete' => $e->getMessage()));
        }

        return new JsonResponse(array('requete' => "sucess"));
    }
    /**
     * @return JsonResponse
     */
    public function addJsonAction()
    {
        return new JsonResponse(array('requete' => "sucess"));
    }
}
