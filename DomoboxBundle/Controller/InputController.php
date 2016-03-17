<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domotique\ReseauBundle\Entity\Log;
use Domotique\ReseauBundle\Entity\Module;

class InputController extends Controller
{

    public function addFuxFakeAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $sensorFluxAdd = new \DateTime(rand(-12, 12).' hour');
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

    public function addJsonAction()
    {

        $em = $this->getDoctrine()->getManager();
        $params = array();
        $logger = $this->get('logger');

        $content = $this->get("request")->getContent();

        $logger->error($content);
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }

        $logger->error($params);
        //recherche module
        $moduleX = $em->getRepository('DomotiqueReseauBundle:Module')
            ->findOneBy(array('adressMac' => $params['mac']));
        if (!$moduleX) {
            $logger->error("Unable to find module entity.");
//
            $module = new Module();
            $module->setId(NULL);
            $module->setAdressMac($params['mac']);
            $module->setAdressIpv4($params['ipv4']);
//
            $em->persist($module);
            $em->flush();
        } else {
            $logger->error("ok !");
            foreach ($params['sensors'] as $k => $v) {
                $log = new Log();
                $sensorType = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($params['sensors'][$k]['sensor type Id']);
                $sensorUnit = $em->getRepository('DomotiqueReseauBundle:SensorUnit')->find($params['sensors'][$k]['sensor unit Id']);
                $log->setModule($moduleX);
                $log->setSensorId($params['sensors'][$k]['sensor Id']);
                $log->setSensorType($sensorType);
                $log->setSensorUnit($sensorUnit);
                $log->setSonsorValue($params['sensors'][$k]['sensor value']);
                $em->persist($log);
            }
            $em->flush();
        }
        return new JsonResponse(array('requete' => "sucess"));
    }
}
