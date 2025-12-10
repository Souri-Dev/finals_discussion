<?php

namespace App\Repository;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityLog>
 */
class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    /**
     * Find activity logs by filters
     * 
     * @return ActivityLog[] Returns an array of ActivityLog objects
     */
    public function findByFilters(?string $action = null, ?string $role = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->orderBy('a.createAt', 'DESC');

        if ($action) {
            $queryBuilder->andWhere('a.action = :action')
                ->setParameter('action', $action);
        }

        if ($role) {
            $queryBuilder->andWhere('a.role LIKE :role')
                ->setParameter('role', '%' . $role . '%');
        }

        if ($dateFrom) {
            $queryBuilder->andWhere('a.createAt >= :dateFrom')
                ->setParameter('dateFrom', new \DateTime($dateFrom));
        }

        if ($dateTo) {
            $queryBuilder->andWhere('a.createAt <= :dateTo')
                ->setParameter('dateTo', new \DateTime($dateTo . ' 23:59:59'));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return ActivityLog[] Returns an array of ActivityLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ActivityLog
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
