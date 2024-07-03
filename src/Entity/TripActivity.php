<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TripActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripActivityRepository::class)]
class TripActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'TripActivities')]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(inversedBy: 'tripActivities')]
    private ?Activity $activity = null;


    #[ORM\Column(type: 'datetime')]
    #[Assert\Expression(
        expression: "this.getStartDate() > this.getTrip().getStartDate()",
        message: "La date de début doit être égale ou postérieure à la date de debut du voyage."
    )]
    private ?\DateTimeInterface $startDate = null;


    #[ORM\Column(type: 'datetime')]
    #[Assert\Expression(
        expression: "this.getEndDate() > this.getStartDate()",
        message: "La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
