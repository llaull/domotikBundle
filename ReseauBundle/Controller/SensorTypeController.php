<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\SensorType;

/**
 * SensorType controller.
 *
 */
class SensorTypeController extends Controller
{
    /**
     * Lists all SensorType entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sensorTypes = $em->getRepository('DomotiqueReseauBundle:SensorType')->findAll();

        return $this->render('@DomotiqueReseau/sensortype/index.html.twig', array(
            'entities' => $sensorTypes,
        ));
    }

    /**
     * Creates a new SensorType entity.
     *
     */
    public function newAction(Request $request)
    {
        $sensorType = new SensorType();
        $form = $this->createForm('Domotique\ReseauBundle\Form\Type\SensorTypeType', $sensorType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sensorType);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_sensor_type_index');
        }

        return $this->render('@DomotiqueReseau/sensortype/new.html.twig', array(
            'sensorType' => $sensorType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SensorType entity.
     *
     */
    public function showAction(SensorType $sensorType)
    {
        $deleteForm = $this->createDeleteForm($sensorType);

        return $this->render('@DomotiqueReseau/sensortype/show.html.twig', array(
            'sensorType' => $sensorType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SensorType entity.
     *
     */
    public function editAction(Request $request, SensorType $sensorType)
    {
        $deleteForm = $this->createDeleteForm($sensorType);
        $editForm = $this->createForm('Domotique\ReseauBundle\Form\Type\SensorTypeType', $sensorType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sensorType);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_sensor_type_edit', array('id' => $sensorType->getId()));
        }

        return $this->render('@DomotiqueReseau/sensortype/edit.html.twig', array(
            'sensorType' => $sensorType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SensorType entity.
     *
     */
    public function deleteAction(Request $request, SensorType $sensorType)
    {
        $form = $this->createDeleteForm($sensorType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sensorType);
            $em->flush();
        }

        return $this->redirectToRoute('admin_domotique_sensor_type_index');
    }

    /**
     * Creates a form to delete a SensorType entity.
     *
     * @param SensorType $sensorType The SensorType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SensorType $sensorType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_domotique_sensor_type_delete', array('id' => $sensorType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
