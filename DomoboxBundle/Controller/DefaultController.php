<?php

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {

    }


    public function getModuleColorAction(Request $request)
    {
        $curling = $this->container->get('commun.curl');

        $module_url = "http://10.0.112.3/rgb/FFFFFF";


        $curl = $curling->getToUrl($module_url, false);
        return new JsonResponse(array('curl' => $curl));


    }


}
