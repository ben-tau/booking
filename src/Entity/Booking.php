<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")(message="Le format doit être une date")
     * @Assert\GreaterThan("today",message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")(message="Le format doit être une date")
     * @Assert\GreaterThan(propertyPath="startDate",message="La date de départ doît être plus éloignée que la date d'arrivée")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appellé à chq fois qu'on crée une réservation
     * @ORM\PrePersist
     *
     * @return Response
     */
    public function prePersist()
    {
        if(empty($this->createdAt))
        {
            $this->createdAt = new \DateTime();
        }

        if(empty($this->amount))
        {
            // prix de l'annonce * le nb de jours

            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }


    public function isBookableDays()
    {
        // il faut connaitre deja les dates réservées
        $notAvailableDays = $this->ad->getNotAvailableDays();

        // il faut connaitre les dates en cours de réservation
        $bookingDays = $this->getDays();

        // comparaison
        $notAvailableDays = array_map(function($day)
        {
            return $day->format('Y-m-d');
        },$notAvailableDays);

        $days = array_map(function($day)
        {
            return $day->format('Y-m-d');
        },$bookingDays);

        // on retourne vrai ou faux
        foreach($days as $day)
        {
            if(array_search($day,$notAvailableDays) !== false) return false;
        }
        return true;
    }


    public function getDays()
    {
        $result = range(
            $this->getStartDate()->getTimeStamp(),
            $this->getEndDate()->getTimeStamp(),
            24 * 60 * 60
        );

        $days = array_map(function($dayTimestamp)
            {
                return new \DateTime(date("Y-m-d",$dayTimestamp));
            },$result);

        return $days;
    }

    // calcul du nb de jours du séjour
    public function getDuration()
    {
        $difference = $this->endDate->diff($this->startDate);
        return $difference->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
