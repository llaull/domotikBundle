<?php

namespace Domotique\ReseauBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Domotique\ReseauBundle\Entity\ModuleNotify;

/**
 * ModuleNotify controller.
 *
 */
class ModuleNotifyController extends Controller
{
    /**
     * Lists all ModuleNotify entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $moduleNotifies = $em->getRepository('DomotiqueReseauBundle:ModuleNotify')->findAll();

        return $this->render('@DomotiqueReseau/modulenotify/index.html.twig', array(
            'entities' => $moduleNotifies,
        ));
    }

    /**
     * Creates a new ModuleNotify entity.
     *
     */
    public function newAction(Request $request)
    {
        $moduleNotify = new ModuleNotify();
        $form = $this->createForm('Domotique\ReseauBundle\Form\Type\ModuleNotifyType', $moduleNotify);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($moduleNotify);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_module_notify_index');
        }

        return $this->render('@DomotiqueReseau/modulenotify/new.html.twig', array(
            'moduleNotify' => $moduleNotify,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ModuleNotify entity.
     *
     */
    public function showAction(ModuleNotify $moduleNotify)
    {
        $deleteForm = $this->createDeleteForm($moduleNotify);

        return $this->render('@DomotiqueReseau/modulenotify/show.html.twig', array(
            'moduleNotify' => $moduleNotify,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ModuleNotify entity.
     *
     */
    public function editAction(Request $request, ModuleNotify $moduleNotify)
    {
        $deleteForm = $this->createDeleteForm($moduleNotify);
        $editForm = $this->createForm('Domotique\ReseauBundle\Form\Type\ModuleNotifyType', $moduleNotify);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($moduleNotify);
            $em->flush();

            return $this->redirectToRoute('admin_domotique_module_notify_edit', array('id' => $moduleNotify->getId()));
        }

        return $this->render('@DomotiqueReseau/modulenotify/edit.html.twig', array(
            'moduleNotify' => $moduleNotify,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ModuleNotify entity.
     *
     */
    public function deleteAction(Request $request, ModuleNotify $moduleNotify)
    {
        $form = $this->createDeleteForm($moduleNotify);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($moduleNotify);
            $em->flush();
        }

        return $this->redirectToRoute('admin_domotique_module_notify_index');
    }

    /**
     * Creates a form to delete a ModuleNotify entity.
     *
     * @param ModuleNotify $moduleNotify The ModuleNotify entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ModuleNotify $moduleNotify)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_domotique_module_notify_delete', array('id' => $moduleNotify->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
