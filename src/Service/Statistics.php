<?php 



    namespace App\Service;
    
    use Doctrine\ORM\EntityManagerInterface;

    class Statistics
    {
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }

        public function getStatistics()
        {
            $users = $this->getUsersCount();
            $ads = $this->getAdsCount();
            $bookings = $this->getBookingsCount();
            $comments = $this->getCommentsCount();
            return compact('users','bookings','ads','comments');
        }

        public function getUsersCount()
        {
            return $this->entityManager->createQuery("SELECT COUNT(u) FROM App\Entity\User u ")->getSingleScalarResult();
        }

        public function getAdsCount()
        {
            return $this->entityManager->createQuery("SELECT COUNT(a) FROM App\Entity\Ad a ")->getSingleScalarResult();
        }

        public function getBookingsCount()
        {
            return $this->entityManager->createQuery("SELECT COUNT(b) FROM App\Entity\Booking b ")->getSingleScalarResult();
        }

        public function getCommentsCount()
        {
            return $this->entityManager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c ")->getSingleScalarResult();
        }

        public function getAdsStats($direction)
        {
            return $this->entityManager->createQuery(
                "SELECT AVG(c.rating) AS note,a.title,a.id,u.firstname,u.lastname,u.avatar 
                 FROM App\Entity\Comment c
                 JOIN c.ad a
                 JOIN a.author u
                 GROUP BY a
                 ORDER BY note $direction")
                 ->setMaxResults(5)
                 ->getResult()
            ;
        }
    }