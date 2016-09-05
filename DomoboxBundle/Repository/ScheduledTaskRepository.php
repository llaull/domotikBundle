<?php
/**
 * Created by PhpStorm.
 * User: hazardl
 * Date: 02/09/2016
 * Time: 12:34
 */

namespace Domotique\DomoboxBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ScheduledTaskRepository extends EntityRepository
{

    /*
     * return les taches
     *  futur et qui n'ont pas commencer
     */
    public function findAllFuturAndNotStart()
    {
        $date_from = new \DateTime();

        return $this->createQueryBuilder('t')
            ->where('t.taskStart = false')
            ->andWhere('t.start <= :date_start')
            ->setParameter('date_start', $date_from)
            ->getQuery()
            ->getResult();

    }

}
