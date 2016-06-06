<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\Log;

/**
 * Log controller.
 *
 */
class LogController extends Controller
{
    /**
     * Lists all Log entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $logs = $em->getRepository('DomotiqueReseauBundle:Log')->findBy(array(), array('created' => 'DESC'), 100, 0);

        return $this->render('@DomotiqueReseau/log/index.html.twig', array(
            'entities' => $logs,
        ));
    }

    /**
     * Creates a new Log entity.
     *
     */
    public function newAction(Request $request)
    {
        $log = new Log();
        $form = $this->createForm('Domotique\ReseauBundle\Form\Type\LogType', $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_log_index');
        }

        return $this->render('@DomotiqueReseau/log/new.html.twig', array(
            'log' => $log,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Log entity.
     *
     */
    public function showAction(Log $log)
    {
        $deleteForm = $this->createDeleteForm($log);

        return $this->render('@DomotiqueReseau/log/show.html.twig', array(
            'log' => $log,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Log entity.
     *
     */
    public function editAction(Request $request, Log $log)
    {
        $deleteForm = $this->createDeleteForm($log);
        $editForm = $this->createForm('Domotique\ReseauBundle\Form\LogType', $log);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_log_edit', array('id' => $log->getId()));
        }

        return $this->render('@DomotiqueReseau/log/edit.html.twig', array(
            'log' => $log,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Log entity.
     *
     */
    public function deleteAction(Request $request, Log $log)
    {
        $form = $this->createDeleteForm($log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($log);
            $em->flush();
        }

        return $this->redirectToRoute('admin_domotique_log_index');
    }

    /**
     * Creates a form to delete a Log entity.
     *
     * @param Log $log The Log entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Log $log)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_domotique_log_delete', array('id' => $log->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
