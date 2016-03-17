<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\SensorType;
use Domotique\ReseauBundle\Form\SensorTypeType;

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

        $entities = $em->getRepository('DomotiqueReseauBundle:SensorType')->findAll();

        return $this->render('DomotiqueReseauBundle:SensorType:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new SensorType entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new SensorType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sensor_type_show', array('id' => $entity->getId())));
        }

        return $this->render('DomotiqueReseauBundle:SensorType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a SensorType entity.
     *
     * @param SensorType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SensorType $entity)
    {
        $form = $this->createForm(new SensorTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_sensor_type_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SensorType entity.
     *
     */
    public function newAction()
    {
        $entity = new SensorType();
        $form   = $this->createCreateForm($entity);

        return $this->render('DomotiqueReseauBundle:SensorType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SensorType entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DomotiqueReseauBundle:SensorType:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SensorType entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorType entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DomotiqueReseauBundle:SensorType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a SensorType entity.
    *
    * @param SensorType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SensorType $entity)
    {
        $form = $this->createForm(new SensorTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_sensor_type_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SensorType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_sensor_type_edit', array('id' => $id)));
        }

        return $this->render('DomotiqueReseauBundle:SensorType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a SensorType entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DomotiqueReseauBundle:SensorType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SensorType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_sensor_type'));
    }

    /**
     * Creates a form to delete a SensorType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_sensor_type_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
