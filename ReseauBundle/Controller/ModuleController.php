<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\Module;
use Domotique\ReseauBundle\Form\ModuleType;

/**
 * Module controller.
 *
 */
class ModuleController extends Controller
{

    /**
     * Lists all Module entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DomotiqueReseauBundle:Module')->findAll();

        return $this->render('DomotiqueReseauBundle:Module:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Module entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Module();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_domotique_modules_show', array('id' => $entity->getId())));
        }

        return $this->render('DomotiqueReseauBundle:Module:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Module entity.
     *
     * @param Module $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Module $entity)
    {
        $form = $this->createForm(new ModuleType(), $entity, array(
            'action' => $this->generateUrl('admin_domotique_modules_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Module entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Module();
        $form = $this->createForm('Domotique\ReseauBundle\Form\ModuleType', $entity);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

//            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('DomotiqueReseauBundle:Module:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));




//        $entity = new Module();
//        $form   = $this->createCreateForm($entity);
//
//        return $this->render('DomotiqueReseauBundle:Module:new.html.twig', array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        ));
    }

    /**
     * Finds and displays a Module entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:Module')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Module entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DomotiqueReseauBundle:Module:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Module entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:Module')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Module entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DomotiqueReseauBundle:Module:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Module entity.
    *
    * @param Module $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Module $entity)
    {
        $form = $this->createForm(new ModuleType(), $entity, array(
            'action' => $this->generateUrl('admin_domotique_modules_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Module entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:Module')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Module entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_domotique_modules_edit', array('id' => $id)));
        }

        return $this->render('DomotiqueReseauBundle:Module:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Module entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DomotiqueReseauBundle:Module')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Module entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_domotique_modules'));
    }

    /**
     * Creates a form to delete a Module entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_domotique_modules_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
