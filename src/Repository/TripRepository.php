<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

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


}
