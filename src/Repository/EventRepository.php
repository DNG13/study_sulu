<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function create(): Event
    {
        return new Event();
    }

    public function save(Event $event): void
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }

    public function remove(int $id): void
    {
        /** @var Event $event */
        $event = $this->getEntityManager()->find(Event::class, $id);
        $this->getEntityManager()->remove($event);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int[] $ids
     *
     * @return Event[]
     */
    public function findByIds(array $ids): array
    {
        $events = $this->findBy(['id' => $ids]);

        $idPositions = array_flip($ids);
        usort($events, function (Event $a, Event $b) use ($idPositions) {
            return $idPositions[$a->getId()] - $idPositions[$b->getId()];
        });

        return $events;
    }
}