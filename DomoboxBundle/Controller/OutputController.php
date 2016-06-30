<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 09/02/2016
 * Time: 16:32
 */

namespace Domotique\DomoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class OutputController extends Controller
{

    public function LogAmChartsAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $rq =
            "SELECT
    -- a.id,
    a.sonsor_value AS value,
    a.sonsor_value AS volume,
    a.created AS date
    -- a.sonsor_unit,
    -- a.sensor_type,
    -- a.sonsor_id,
    -- m.emplacement_id
FROM
    domotique__sensor_log AS A
        INNER JOIN
    (SELECT
        id, AVG(sonsor_value) AS sonsor_value
    FROM
        domotique__sensor_log
    WHERE
        sonsor_unit NOT IN (7)
            AND sonsor_unit IN (2)
    GROUP BY id) maxiValue ON A.id = maxiValue.id
        INNER JOIN
    domotique__module AS M ON a.module_id = M.id
GROUP BY YEAR(A.created) , MONTH(A.created) , DAY(A.created) , A.sonsor_unit , A.sensor_type , A.sonsor_id";


        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->execute();
        $results = $statement->fetchAll();


//        /*  $entities = array("date" => "Fri Jun 03 2016 00:00:00 GMT+0200 (Paris, Madrid (heure d’été))",
//              "value" => 53,
//              "Open" => 3,
//              "Low" => 3,
//              "Close" => 3,
//              "volume" => 3,
//          );*/
//
//        $date = new \Datetime();
//        $start = $date->format('d-m-Y ');
//        // $newDate = $date->add(new DateInterval('PT10H30S'));
//
//        for ($i = 0; $i < 100; $i++) {
//
//            $visits = rand($i * 5 , 100 );
//            $hits = rand($i * 9 , 100 );
//            $views = rand($i * 15 , 100 );
//
//            $entities[] = array(
//                'date' => $start,
//                'value'=>$visits,
//                'volume' => $views
//            );
//        }
//
////        die(var_dump($qqq))

        return new JsonResponse($results);
    }

    public function logMoyenneAction($unit, $spot)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $this->getDoctrine()->getRepository('DomotiqueReseauBundle:Log');
        $entities = $entities->getMoyenHourGroupByModule($em, $unit, $spot);

        return new JsonResponse($entities);
    }

    public function getCurrentDateAction()
    {
        $now = new \DateTime();
        $currentDate = $now->format('d-m-Y');
        $currentHour = $now->format('G:i:s');

        $return = array("date" => $currentDate, "hour" => $currentHour);
        return new JsonResponse($return);
    }

    public function getCurrentValueJsonAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $this->getDoctrine()->getRepository('DomotiqueReseauBundle:Log');
        $entities = $entities->getCurrentValue($em);

        return new JsonResponse($entities);
    }

    /*
     *
     */
    public function getDomoboxNotifyAction()
    {
        $em = $this->getDoctrine()->getManager();

        $moduleNotifies = $em->getRepository('DomotiqueReseauBundle:ModuleNotify')->findBy(array(), array('created' => 'DESC'));

        return $this->render('DomotiqueDomoboxBundle:ui_notification:index.html.twig', array(
            'entities' => $moduleNotifies,
        ));
    }

    /*
     * envoie une image en base64
     *
     */
    public function getWebcamAction(Request $request){

        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $data = $request->request->get('data');
            $params = current(json_decode($data, true));

            $image = "webcam.png?" . rand() . "";
            $file = base64_encode(file_get_contents($params['webcam']));
            $headers = array(
                'Content-Type' => 'image/jpg',
                'Pragma-directive' => 'no-cache',
                'Cache-directive' => 'no-cache',
                'Cache-control' => 'no-cache',
                'Pragma:' => 'no-cache',
                'Expires:' => 0,
                'Content-Disposition' => 'inline; filename="' . $image . '"');

            return new Response($file, 200, $headers);

        } else {
            $return = array("error" => "anonymous", "image" => "R0lGODlhPQBEAPeoAJosM//AwO/AwHVYZ/z595kzAP/s7P+goOXMv8+fhw/v739/f+8PD98fH/8mJl+fn/9ZWb8/PzWlwv///6wWGbImAPgTEMImIN9gUFCEm/gDALULDN8PAD6atYdCTX9gUNKlj8wZAKUsAOzZz+UMAOsJAP/Z2ccMDA8PD/95eX5NWvsJCOVNQPtfX/8zM8+QePLl38MGBr8JCP+zs9myn/8GBqwpAP/GxgwJCPny78lzYLgjAJ8vAP9fX/+MjMUcAN8zM/9wcM8ZGcATEL+QePdZWf/29uc/P9cmJu9MTDImIN+/r7+/vz8/P8VNQGNugV8AAF9fX8swMNgTAFlDOICAgPNSUnNWSMQ5MBAQEJE3QPIGAM9AQMqGcG9vb6MhJsEdGM8vLx8fH98AANIWAMuQeL8fABkTEPPQ0OM5OSYdGFl5jo+Pj/+pqcsTE78wMFNGQLYmID4dGPvd3UBAQJmTkP+8vH9QUK+vr8ZWSHpzcJMmILdwcLOGcHRQUHxwcK9PT9DQ0O/v70w5MLypoG8wKOuwsP/g4P/Q0IcwKEswKMl8aJ9fX2xjdOtGRs/Pz+Dg4GImIP8gIH0sKEAwKKmTiKZ8aB/f39Wsl+LFt8dgUE9PT5x5aHBwcP+AgP+WltdgYMyZfyywz78AAAAAAAD///8AAP9mZv///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAKgALAAAAAA9AEQAAAj/AFEJHEiwoMGDCBMqXMiwocAbBww4nEhxoYkUpzJGrMixogkfGUNqlNixJEIDB0SqHGmyJSojM1bKZOmyop0gM3Oe2liTISKMOoPy7GnwY9CjIYcSRYm0aVKSLmE6nfq05QycVLPuhDrxBlCtYJUqNAq2bNWEBj6ZXRuyxZyDRtqwnXvkhACDV+euTeJm1Ki7A73qNWtFiF+/gA95Gly2CJLDhwEHMOUAAuOpLYDEgBxZ4GRTlC1fDnpkM+fOqD6DDj1aZpITp0dtGCDhr+fVuCu3zlg49ijaokTZTo27uG7Gjn2P+hI8+PDPERoUB318bWbfAJ5sUNFcuGRTYUqV/3ogfXp1rWlMc6awJjiAAd2fm4ogXjz56aypOoIde4OE5u/F9x199dlXnnGiHZWEYbGpsAEA3QXYnHwEFliKAgswgJ8LPeiUXGwedCAKABACCN+EA1pYIIYaFlcDhytd51sGAJbo3onOpajiihlO92KHGaUXGwWjUBChjSPiWJuOO/LYIm4v1tXfE6J4gCSJEZ7YgRYUNrkji9P55sF/ogxw5ZkSqIDaZBV6aSGYq/lGZplndkckZ98xoICbTcIJGQAZcNmdmUc210hs35nCyJ58fgmIKX5RQGOZowxaZwYA+JaoKQwswGijBV4C6SiTUmpphMspJx9unX4KaimjDv9aaXOEBteBqmuuxgEHoLX6Kqx+yXqqBANsgCtit4FWQAEkrNbpq7HSOmtwag5w57GrmlJBASEU18ADjUYb3ADTinIttsgSB1oJFfA63bduimuqKB1keqwUhoCSK374wbujvOSu4QG6UvxBRydcpKsav++Ca6G8A6Pr1x2kVMyHwsVxUALDq/krnrhPSOzXG1lUTIoffqGR7Goi2MAxbv6O2kEG56I7CSlRsEFKFVyovDJoIRTg7sugNRDGqCJzJgcKE0ywc0ELm6KBCCJo8DIPFeCWNGcyqNFE06ToAfV0HBRgxsvLThHn1oddQMrXj5DyAQgjEHSAJMWZwS3HPxT/QMbabI/iBCliMLEJKX2EEkomBAUCxRi42VDADxyTYDVogV+wSChqmKxEKCDAYFDFj4OmwbY7bDGdBhtrnTQYOigeChUmc1K3QTnAUfEgGFgAWt88hKA6aCRIXhxnQ1yg3BCayK44EWdkUQcBByEQChFXfCB776aQsG0BIlQgQgE8qO26X1h8cEUep8ngRBnOy74E9QgRgEAC8SvOfQkh7FDBDmS43PmGoIiKUUEGkMEC/PJHgxw0xH74yx/3XnaYRJgMB8obxQW6kL9QYEJ0FIFgByfIL7/IQAlvQwEpnAC7DtLNJCKUoO/w45c44GwCXiAFB/OXAATQryUxdN4LfFiwgjCNYg+kYMIEFkCKDs6PKAIJouyGWMS1FSKJOMRB/BoIxYJIUXFUxNwoIkEKPAgCBZSQHQ1A2EWDfDEUVLyADj5AChSIQW6gu10bE/JG2VnCZGfo4R4d0sdQoBAHhPjhIB94v/wRoRKQWGRHgrhGSQJxCS+0pCZbEhAAOw==");
            return new JsonResponse($return);

        }

    }

}
