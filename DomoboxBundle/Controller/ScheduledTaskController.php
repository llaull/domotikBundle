<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Domotique\DomoboxBundle\Entity\ScheduledTask;
use Symfony\Component\HttpFoundation\JsonResponse;

class ScheduledTaskController extends Controller
{
    public function newAction(Request $request)
    {
        $scheduleTask = new ScheduledTask();
        $form = $this->createForm('Domotique\DomoboxBundle\Form\Type\ScheduledTaskType', $scheduleTask);
        $form->handleRequest($request);

        $logger = $this->get('logger');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scheduleTask);

            try {
                $em->flush();
                return new JsonResponse(array('query' => 'sucess'));
            } catch (\Exception $e) {
                $logger->critical($e->getMessage());
                return new JsonResponse(array('query' => $e->getMessage()));
            }

        }

        return $this->render('DomotiqueDomoboxBundle:ScheduledTask:form.html.twig', array(
            'scheduleTask' => $scheduleTask,
            'form' => $form->createView(),
        ));
    }

}
