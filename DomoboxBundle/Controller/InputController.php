<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $logger = $this->get('logger');

        $moduleId = 1;
        $sensorId = 1;
        $sensorTypeId = 8;
        $sensorUnitId = 7;
        $sensorValue = 1;

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
            $logger->critical($e->getMessage());
            return new JsonResponse(array('requete' => $e->getMessage()));
        }

        return new JsonResponse(array('requete' => "sucess"));
    }

    /**
     * @return JsonResponse
     */
    public function addJsonAction(Request $request)
    {

        $content = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        //si le contenu n'est pas vide on decode la string json en tableau php
        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $moduleX = $em->getRepository('DomotiqueReseauBundle:Module')->findOneBy(array('adressMac' => $params['mac']));

        /*
         * si pas de module trouvé
         */
        if (!$moduleX) {
            $logger->error("Unable to find module entity. new module created");

            $module = new Module();
            $module->setId(NULL);
            $module->setName("nouveau module");
            $module->setAdressMac($params['mac']);
            $module->setAdressIpv4($params['ipv4']);

            $em->persist($module);
            $em->flush();

            /*
             * si module touvé on creé un nouveau log
             */
        } else {

            /*
             * mets a jour l'ip du module
             */
            if ($moduleX->getAdressIpv4() != $params['ipv4']) {

                $moduleX->setAdressIpv4($params['ipv4']);
                $em->persist($moduleX);

            }

            // creation d'un log par donnée de sonde
            foreach ($params['sensors'] as $k => $v) {

                //va chercher l'objet par l'id
                $sensorType = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($params['sensors'][$k]["sensor type Id"]);
                $sensorUnit = $em->getRepository('DomotiqueReseauBundle:SensorUnit')->find($params['sensors'][$k]["sensor unit Id"]);

                $log = new Log();

                $log->setModule($moduleX);
                $log->setSensorId($params['sensors'][$k]['sensor Id']);
                $log->setSensorType($sensorType);
                $log->setSensorUnit($sensorUnit);
                $log->setSonsorValue($params['sensors'][$k]['sensor value']);
                $em->persist($log);
            }

            //
            $em->flush();
        }

        return new JsonResponse(array('requete' => "sucess"));
    }
}
