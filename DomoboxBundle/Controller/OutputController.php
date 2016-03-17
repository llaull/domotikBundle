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
        $currentDateFr = $now->format('d-m-Y');
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
    u.slug,
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
}
