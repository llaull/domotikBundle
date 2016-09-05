<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\Module;

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

        $modules = $em->getRepository('DomotiqueReseauBundle:Module')->findAll();

        return $this->render('@DomotiqueReseau/module/index.html.twig', array(
            'entities' => $modules,
        ));
    }

    /**
     * Creates a new Module entity.
     *
     */
    public function newAction(Request $request)
    {
        $module = new Module();
        $form = $this->createForm('Domotique\ReseauBundle\Form\Type\ModuleType', $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();

            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Category added");

            return $this->redirectToRoute('admin_domotique_module_index');
        }

        return $this->render('@DomotiqueReseau/module/new.html.twig', array(
            'module' => $module,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Module entity.
     *
     */
    public function showAction(Module $module)
    {
        $deleteForm = $this->createDeleteForm($module);

        return $this->render('@DomotiqueReseau/module/show.html.twig', array(
            'module' => $module,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Module entity.
     *
     */
    public function editAction(Request $request, Module $module)
    {
        $deleteForm = $this->createDeleteForm($module);
        $editForm = $this->createForm('Domotique\ReseauBundle\Form\Type\ModuleType', $module);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_module_edit', array('id' => $module->getId()));
        }

        return $this->render('@DomotiqueReseau/module/edit.html.twig', array(
            'module' => $module,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Module entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DomotiqueReseauBundle:Module')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Texte entity.');
        }

        try {
            $em->remove($entity);
            $em->flush();
            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Suppression réalisé avec succèss");
        } catch (\PDOException $e) {
            $this->get('ras_flash_alert.alert_reporter')->addError("Suppression impossible : des articles utilise cette categorie");
        }


        return $this->redirect($this->generateUrl('admin_domotique_module_index'));

        /*$form = $this->createDeleteForm($module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($module);
            $em->flush();
        }

        return $this->redirectToRoute('admin_domotique_module_index');
        */
    }

    /**
     * Creates a form to delete a Module entity.
     *
     * @param Module $module The Module entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Module $module)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_domotique_module_delete', array('id' => $module->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
