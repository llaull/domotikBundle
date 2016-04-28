<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Domotique\DomoboxBundle\Entity\ScheduledTask;

class ScheduledTaskController extends Controller
{
    public function newAction(Request $request)
    {
        $scheduleTask = new ScheduledTask();
        $form = $this->createForm('Domotique\DomoboxBundle\Form\Type\ScheduledTaskType', $scheduleTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scheduleTask);
            $em->flush();

            return $this->redirectToRoute('domotiquebundle_domobox');
        }

        return $this->render('DomotiqueDomoboxBundle:ScheduledTask:add.html.twig', array(
            'emplacement' => $scheduleTask,
            'form' => $form->createView(),
        ));
    }

}
