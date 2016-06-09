<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $emplacements = $em->getRepository('DomotiqueReseauBundle:Emplacement')->findBy(array(), array('name' => 'ASC'));

        //
        $em = $this->getDoctrine()->getEntityManager();
        $modules = $this->getDoctrine()->getRepository('DomotiqueReseauBundle:Log');
        $modules = $modules->getCurrentValue($em);

        return $this->render('DomotiqueDomoboxBundle:Default:modules.html.twig', array(
            'emplacements' => $emplacements,
            'modules' => $modules,
        ));

    }

    public function graphAction()
    {
        return $this->render('DomotiqueDomoboxBundle:Default:graphique.html.twig');
    }


    public function rgbAction()
    {
        return $this->render('DomotiqueDomoboxBundle:Default:testRGB.html.twig');
    }

    public function videoAction()
    {
        return $this->render('DomotiqueDomoboxBundle:Default:videosurveillance.html.twig');
    }


    public function setModuleColorAction(Request $request)
    {
//        json
        $data = $request->request->get('data');
        $params = json_decode($data, true);

        $curling = $this->container->get('commun.curl');

        $module_url = "http://".$params[0]['module']."/rgb/".$params[2]['color'];


        $curl = $curling->getToUrl($module_url, false);
        $reponse = array('curl' => $curl, 'set' => $module_url);
        return new JsonResponse($reponse);


    }

}
