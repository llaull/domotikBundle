<?php
namespace Domotique\ReseauBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository
{

    /**
     * @param $em
     * @param $unit
     * @param $spot
     * @return mixed
     */
    public function getMoyenHourGroupByModule($em, $unit, $spot)
    {
        $rq = "
        SELECT  DATE_FORMAT(logs.created, '%Y-%m-%d') AS jour,
               CONCAT(HOUR(logs.created),':00') AS heure,
               ROUND(AVG(`sonsor_value`),2) AS moyenne,
               ROUND(MAX(`sonsor_value`),2) AS maxi,
               ROUND(MIN(`sonsor_value`),2) AS mini,
                CONCAT(DATE_FORMAT(logs.created, '%Y-%m-%d'),' ',CONCAT(HOUR(logs.created),':00')) AS l,
               `module_id`,
               `sonsor_id`,
               unit.name AS sonsor_unitee,
               unit.symbole AS sonde_symbole,
               type.name AS sondy_type,
               logs.id AS ID,
               info.emplacement_id
        FROM domotique__sensor_log AS logs
        INNER JOIN domotique__sensor_unit AS unit ON unit.id = logs.sonsor_unit
        INNER JOIN domotique__sensor_type AS type ON type.id = logs.sensor_type
        INNER JOIN domotique__module AS info on info.id = logs.module_id
        WHERE logs.created > DATE_SUB(NOW(), INTERVAL 24 HOUR)
        AND type.id in (2,3,4)
        AND unit.id = :unit
        AND info.emplacement_id = :emplacement
        GROUP BY YEAR(logs.created),
                 MONTH(logs.created),
                 DAY(logs.created),
                 HOUR(logs.created),
                 -- MINUTE(created),
                 `module_id`,
                 `sonsor_id`,
                 `sensor_type`,
                 `sonsor_unit`
               --  ,info.emplacement_id
        ORDER BY jour, heure ASC
        ";

        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->bindValue("unit", $unit);
        $statement->bindValue("emplacement", $spot);

        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getCurrentValue($em)
    {
        $rq = 'SELECT
    l.id,
    l.module_id,
    l.created AS date,
    l.sonsor_unit,
    l.sensor_type,
    l.sonsor_value,
    m.name,
    m.ipv4,
    t.name AS nom_sonde,
    E.name AS emplacement,
    u.symbole as unitee_symbole
FROM
    domotique__sensor_log AS l
        INNER JOIN
    (SELECT
        module_id, sonsor_unit, sensor_type, MAX(created) AS max_temp
    FROM
        domotique__sensor_log
    WHERE
         created > CURDATE()
        AND sensor_type <> 1
        -- AND created > SUBTIME(NOW(),"02:00:00")
      -- AND DATE_SUB(CURTIME(),INTERVAL 1 HOUR) >= created
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

        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getMaxMinValue($em, $type, $unitOut = 7)
    {
        $unitOut = "sonsor_unit NOT IN ($unitOut)";

        $rq =
        "SELECT a.id
FROM
    domotique__sensor_log a
        INNER JOIN
    (SELECT
        id, $type(sonsor_value) AS sonsor_value
    FROM
        domotique__sensor_log
    WHERE
        $unitOut
    GROUP BY id) maxiValue ON a.id = maxiValue.id
GROUP BY HOUR(created) , YEAR(created) , MONTH(created) , DAY(created) , sonsor_unit , sensor_type , sonsor_id";



        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    
    public function getMoyenneValue($em,$unitIn)
    {
        $unitIn = "sonsor_unit IN (2,3)";
        $rq =
            "SELECT
    A.created AS date,
    A.sonsor_unit as unit,
    A.sonsor_value
FROM
    domotique__sensor_log AS A
        INNER JOIN
    (SELECT
        id, AVG(sonsor_value) AS sonsor_value
    FROM
        domotique__sensor_log
     WHERE
        $unitIn
    GROUP BY id) maxiValue ON A.id = maxiValue.id
        INNER JOIN
    domotique__module AS M ON A.module_id = M.id
GROUP BY YEAR(A.created) , MONTH(A.created) , DAY(A.created) , HOUR(A.created), A.sonsor_unit , A.sensor_type , A.sonsor_id";

        $connection = $em->getConnection();
        $statement = $connection->prepare($rq);
        $statement->execute();
        $results = $statement->fetchAll();

        // creation d'un tableau avec la date comme key (evite les doublonb)
        foreach ($results as $k => $v):
            if ($v['unit'] == "2"):
                $unit = 'temp';
            else:
                $unit = 'humi';
            endif;

            $array[$v['date']][$unit]  = $v['sonsor_value'];
        endforeach;

        // creation d'un tableau final avec les differentes valeurs
        foreach ($array as $k => $v):
            $temp = !empty($v['temp']) ? $v['temp'] : NULL;
            $humi = !empty($v['humi']) ? $v['humi'] : NULL;
            $newArray[] = array('date' => $k, 'temp' => $temp, 'humi' => $humi);
        endforeach;

        return $newArray;
    }
}
