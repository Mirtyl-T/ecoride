<?php

namespace App\Repository;

use App\Entity\Trip;
<<<<<<< HEAD
=======
use App\Entity\Vehicule;
>>>>>>> ancien-master
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
<<<<<<< HEAD
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
=======
>>>>>>> ancien-master
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

<<<<<<< HEAD
//    /**
//     * @return Trip[] Returns an array of Trip objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
=======
    public function searchTrips(
        ?string $from,
        ?string $to,
        ?\DateTimeInterface $date,
        ?float $prixMax = null,
        ?int $placesMin = null,
        ?string $fuelType,
        ?string $preferences
    ): array {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.status = :status')
            ->leftJoin('t.vehicule', 'v')
            ->setParameter('status', 'prévu');

        if ($from) {
            $from = mb_strtolower(trim($from));
            $qb->andWhere('LOWER(t.ville_depart) LIKE :from')
            ->setParameter('from', "%{$from}%");
        }

        if ($to) {
            $to = mb_strtolower(trim($to));
            $qb->andWhere('LOWER(t.ville_arrivee) LIKE :to')
            ->setParameter('to', "%{$to}%");
        }

        if ($date) {
            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);
            $qb->andWhere('t.date_depart BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);
        }

        if ($prixMax !== null) {
            $qb->andWhere('t.prix <= :prixMax')
            ->setParameter('prixMax', $prixMax);
        }

        if ($placesMin !== null) {
            $qb->andWhere('t.places_dispo >= :placesMin')
            ->setParameter('placesMin', $placesMin);
        }
        if ($preferences !== null){
            $qb->andWhere('v.preferences = :preferences')
            ->setParameter('preferences', $preferences);
        }
        if ($fuelType !== null){
            $qb->andWhere('v.fuelType = :fuelType')
            ->setParameter('fuelType', $fuelType);
        }

        return $qb->orderBy('t.date_depart', 'ASC')
                ->getQuery()
                ->getResult();
    }
    public function findWithVehicule(int $id): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.vehicule', 'v')
            ->addSelect('v')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


>>>>>>> ancien-master
}
