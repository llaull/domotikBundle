<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 21/06/2016
 * Time: 14:46
 */

namespace Domotique\ReseauBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Domotique\ReseauBundle\Entity\Log;

class LogsCleanCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('domotique:reseau:log:clean')
            ->setDescription('nettoye la table log en gardant les max et min');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //doctrine
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $entities = $em->getRepository('DomotiqueReseauBundle:Log');
        $entitiesMin = $entities->getMaxMinValue($em,'MIN');
        $entitiesMax = $entities->getMaxMinValue($em,'MAX');

        $entities = array_merge($entitiesMin, $entitiesMax);

        foreach($entities as $v):
            $donnees[] = $v['id'];
        endforeach;

        $rq ="DELETE FROM domotique__sensor_log WHERE id NOT IN ('".join("','", $donnees)."')";
        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        var_dump($statement->execute());

    }
}
