<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Domotique\ReseauBundle\Entity\Log;
use Domotique\ReseauBundle\Entity\Module;

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
        $sensorTypeId = 2;
        $sensorUnitId = 2;
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

        $request = Request::createFromGlobals();


        die(var_dump($request->query->all()));

        return new JsonResponse(array('requete' => "sucess"));
    }
}
