<?php

namespace Domotique\ReseauBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


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
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $moduleRgb = $em->getRepository("DomotiqueReseauBundle:Log")
            ->createQueryBuilder('log')
            ->select('IDENTITY(log.module), IDENTITY(log.sensorType) AS sensorType,
                module.adressIpv4 AS ip')
            ->leftJoin('DomotiqueReseauBundle:Module', 'module', \Doctrine\ORM\Query\Expr\Join::WITH, 'log.module = module.id')
             ->where('log.sensorType = 8')
            ->groupBy('log.module')
            ->getQuery()
            ->getResult();

        foreach ($moduleRgb as $module) {
            $output->writeln($module['ip']);
        }
    }
}
