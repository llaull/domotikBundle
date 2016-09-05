<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 21/06/2016
 * Time: 14:46
 */

namespace Domotique\DomoboxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('domotique:reseau:cron')
            ->setDescription('execute des taches sur les module');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //doctrine
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $task = $em->getRepository('DomotiqueDomoboxBundle:ScheduledTask')->findAllFuturAndNotStart();

        $curling = $this->getContainer()->get('commun.curl');


        foreach ($task as $value) {

            // formatage pour mes modules
            $valeur = substr($value->getValeur(), 1, 6);
            $action = strtolower($value->getAction());
            $url = "http://" . $value->getModule()->getAdressIpv4() . "/" . $action . '/' . $valeur;

            // action envoyer en Curl
            $curl = $curling->getToUrl($url, false);

            // si le retour de curl n'est pas vide
            if (!empty($curl)) {
                $params = json_decode($curl, true);
            }

            //si la valeur envoyer est la meme que retourne par le module
            if (strtolower(substr($params["coulor"], 0, 6)) == $valeur) {
                $output->writeln("ok");

            }


        }

    }
}
