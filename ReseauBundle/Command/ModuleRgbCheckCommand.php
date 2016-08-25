<?php

namespace Domotique\ReseauBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domotique\ReseauBundle\Entity\Log;

class ModuleRgbCheckCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('domotique:reseau:module:rgb:check')
            ->setDescription('check si les modules rgb');
    }


    /**
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $curling = $this->getContainer()->get('commun.curl');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $sensorFluxAdd = new \DateTime();
        $logger =  $this->getContainer()->get('logger');
        /*
         *
         */


        /**/

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $moduleRgb = $em->getRepository("DomotiqueReseauBundle:Log")
            ->createQueryBuilder('log')
            ->select('IDENTITY(log.module), IDENTITY(log.sensorType) AS sensorType,
                module.adressIpv4 AS ip, module.id AS module_ID')
            ->leftJoin('DomotiqueReseauBundle:Module', 'module', \Doctrine\ORM\Query\Expr\Join::WITH, 'log.module = module.id')
            ->where('log.sensorType = 8')
            ->groupBy('log.module')
            ->getQuery()
            ->getResult();

        foreach ($moduleRgb as $module) {

            $module_url = "http://" . $module['ip'] . "/ping/";


            $curl = $curling->getToUrl($module_url, false);

            $response = json_decode($curl,true);

            if($response["coulor"] == "pong"){

                $output->writeln($module['module_ID']);
                $output->writeln($response);
                $output->writeln("---------");

                $moduleId = $module['module_ID'];
                $sensorId = 1;
                $sensorTypeId = 8;
                $sensorUnitId = 7;
                $sensorValue = 1;


                $moduleX = $em->getRepository('DomotiqueReseauBundle:Module')->find($moduleId);
                $sensorType = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($sensorTypeId);
                $sensorUnit = $em->getRepository('DomotiqueReseauBundle:SensorUnit')->find($sensorUnitId);

                $log = new Log();
                $log->setModule($moduleX);
                $log->setSensorId($sensorId);
                $log->setSensorType($sensorType);
                $log->setSensorUnit($sensorUnit);
                $log->setSonsorValue($sensorValue);
                $log->setCreated($sensorFluxAdd);
                $em->persist($log);

            } // fin if reponse

        }// fin boucle des modules

        try {
            $em->flush();
        } catch (\Doctrine\ORM\EntityNotFoundException $e) {
            $logger->critical($e->getMessage());
            return new JsonResponse(array('requete' => $e->getMessage()));
        }
    }
}
