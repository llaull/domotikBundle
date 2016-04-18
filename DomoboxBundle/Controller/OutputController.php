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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class OutputController extends Controller
{

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

    public function getCurrentValueAction()
    {

        $rq = 'SELECT
    l.id,
    l.module_id,
    l.created AS date,
    l.sonsor_unit,
    l.sensor_type,
    l.sonsor_value,
    m.name,
    m.mac,
    t.name AS nom_sonde,
    E.name AS emplacement
FROM
    domotique__sensor_log AS l
        INNER JOIN
    (SELECT
        module_id, sonsor_unit, sensor_type, MAX(created) AS max_temp
    FROM
        domotique__sensor_log
    WHERE
         created > CURDATE()
        AND
       -- created > SUBTIME(NOW(),"02:00:00")
      --   AND
      DATE_SUB(CURTIME(),INTERVAL 1 HOUR) >= created
    GROUP BY module_id , sonsor_unit , sensor_type) AS b ON b.module_id = l.module_id
        AND b.sonsor_unit = l.sonsor_unit
        AND b.max_temp = l.created
        LEFT JOIN
    domotique__module AS m ON l.module_id = m.id
        LEFT JOIN
    domotique__sensor_unit AS u ON l.sonsor_unit = u.id
        LEFT JOIN
    domotique__sensor_type AS t ON l.sensor_type = t.id
        LEFT JOIN
    domotique__module_emplacement AS E ON m.emplacement_id = E.id
WHERE
    l.created > CURDATE()
ORDER BY module_id , sensor_type , sonsor_unit';

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->execute();
        $results = $statement->fetchAll();

        return new JsonResponse($results);

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
            $return = array("error" => "anonymous", "image" => "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");
            return new JsonResponse($return);

        }

    }

}
