<?php

namespace Domotique\ReseauBundle\Command;


use Domotique\ReseauBundle\Entity\ModuleNotify;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ModuleCheckCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('domotique:reseau:module:check')
            ->setDescription('check si les modules envoient leurs flux dans l\'heure sinon envoie un mail');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //doctrine
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $output->writeln("_______________                                       back");
        // check les modules revenu depuis 5 min
        $this->ModuleBackCheck($em, $output);
        $output->writeln("_______________                                       lost");
        //check les modules qui n'ont rien envoyer depuis 10 min
        $this->ModuleLostCheck($em, $output);
    }

    protected function ModuleLostCheck($em, $output)
    {

        //charge tout les modules
        $entities = $em->getRepository('DomotiqueReseauBundle:Module')->findAll();


        // pour chaque module
        foreach ($entities as $value) {

            //on va chercher son dernier envoie
            $entities = $em->getRepository('DomotiqueReseauBundle:Log')->findOneBy(
                array('module' => $value->getId()), array('created' => 'DESC'));

            // si log avec ce module
            if ($entities) {

                $output->writeln("id du module " . $value->getId());
                $output->writeln("id du log " . $entities->getId());
                $output->writeln('il y a '.$this->betweenDateToMinutes($entities->getCreated()));

                if ($this->betweenDateToMinutes($entities->getCreated()) > 10) {



                    //on regarde si la notification à deja été generé
                    $checkNotifiy = $em->getRepository('DomotiqueReseauBundle:ModuleNotify')->findOneBy(
                        array('module' => $value->getId(), 'status' => 'lost'));

                    //sinon on cree la notification
                    if (!$checkNotifiy){
                        $notif = new ModuleNotify();
                        $notif->setLog($entities);
                        $notif->setModule($value);
                        $notif->setStatus("lost");
                        $em->persist($notif);
                        $em->flush();

                        $output->writeln('new lost');
                        //il faut envoyer le mail ici
                        $this->sendMail($output, 'perdu', $notif);

                    } // fin si la notication estdeja dans le systeme
                } // fin if 10 min
            } // fin if
        } //fin boucle des modules

    }

    protected function ModuleBackCheck($em,$output)
    {

        //charge tout les modules
        $entities = $em->getRepository('DomotiqueReseauBundle:Module')->findAll();


        // pour chaque module
        foreach ($entities as $value) {

            //on va chercher son dernier envoie
            $entities = $em->getRepository('DomotiqueReseauBundle:Log')->findOneBy(
                array('module' => $value->getId()), array('created' => 'DESC'));

            // si log avec ce module
            if ($entities) {

                $output->writeln("id du module " . $value->getId());
                $output->writeln("id du log " . $entities->getId());
                $output->writeln('il y a '.$this->betweenDateToMinutes($entities->getCreated()));

              //  if ($this->betweenDateToMinutes($entities->getCreated()) < 10) {


                    //on regarde si la notification à deja été generé
                    $checkNotifiy = $em->getRepository('DomotiqueReseauBundle:ModuleNotify')->findOneBy(
                        array('module' => $value->getId(),'status' => 'back'));

                    //sinon on cree la notification
                    if (!$checkNotifiy){
                        $notif = new ModuleNotify();
                        $notif->setLog($entities);
                        $notif->setModule($value);
                        $notif->setStatus("back");
                        $em->persist($notif);
                        $em->flush();

                        $output->writeln('new back');
                        //il faut envoyer le mail ici
                        $this->sendMail($output, 'back', $notif);

                    } // fin si la notication estdeja dans le systeme

              //  } // fin if 10 min
            } // fin if
        } //fin boucle des modules

    }

    /**
     * retourne en minute la difference entre deux date
     * @param $objDate
     * @return int|mixed
     */
    protected function betweenDateToMinutes($objDate)
    {

        $diff = date_diff(new \DateTime(), $objDate);
        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;

        return $minutes;
    }

    protected function sendMail($output, $subject, $data)
    {
        $lastLog = $data->getLog()->getCreated('date');

        $mailPerso = $this->getContainer()->getParameter('mon_mail');
        $message = \Swift_Message::newInstance()
            ->setSubject('la connexion de ' . $data->getLog()->getModule()->getName() . ' est ' . $subject . ' depuis le ' . $lastLog->format('d-m-Y G:i:s'))
            ->setFrom('Gargamail@llovem.eu')
            ->setTo($mailPerso)
            ->setCharset('UTF-8')
            ->setContentType('text/html')
            ->setBody($this->getContainer()->get('templating')->render('DomotiqueReseauBundle:Email:default.html.twig',
                array('data' => $data)));

        try {
            $this->getContainer()->get('mailer')->send($message);
        } catch (\Swift_TransportException $e) {
            $output->writeln($e->getMessage());
        }

    }

}
