<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DomotiqueDomoboxBundle:Default:index.html.twig');
    }


    public function setModuleColorAction(Request $request)
    {
//        json
        $data = $request->request->get('data');
        $params = json_decode($data, true);

        $curling = $this->container->get('commun.curl');

        $module_url = "http://".$params[0]['module']."/rgb/".$params[2]['color'];


        $curl = $curling->getToUrl($module_url, false);
        return new JsonResponse(array('curl' => $curl, 'set' => $module_url));


    }


}
