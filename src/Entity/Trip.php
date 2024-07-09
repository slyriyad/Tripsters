<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ORM\HasLifecycleCallbacks()]

class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThanOrEqual(
        value: 'today',
        message: "La date de début doit être égale ou postérieure à aujourd'hui."
    )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Expression(
        expression: "this.getEndDate() > this.getStartDate()",
        message: "La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column(nullable: true)]
    private ?int $budget = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    /**
     * @var Collection<int, TripUser>
     */
    #[ORM\OneToMany(targetEntity: TripUser::class, mappedBy: 'trip')]
    private Collection $tripUsers;

    /**
     * @var Collection<int, TripActivity>
     */
    #[ORM\OneToMany(targetEntity: TripActivity::class, mappedBy: 'trip')]
    private Collection $TripActivities;

    /**
     * @var Collection<int, expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'trip')]
    private Collection $expenses;

    public function __construct()
    {
        $this->tripUsers = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(?int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreationDateValue(): void
    {
        $this->creationDate = new \DateTime();
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection<int, TripUser>
     */
    public function getTripUsers(): Collection
    {
        return $this->tripUsers;
    }

    public function addTripUser(TripUser $tripUser): static
    {
        if (!$this->tripUsers->contains($tripUser)) {
            $this->tripUsers->add($tripUser);
            $tripUser->setTrip($this);
        }

        return $this;
    }

    public function removeTripUser(TripUser $tripUser): static
    {
        if ($this->tripUsers->removeElement($tripUser)) {
            // set the owning side to null (unless already changed)
            if ($tripUser->getTrip() === $this) {
                $tripUser->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TripActivity>
     */
    public function getTripActivities(): Collection
    {
        return $this->TripActivities;
    }

    public function addTripActivity(TripActivity $tripActivity): static
    {
        if (!$this->TripActivities->contains($tripActivity)) {
            $this->TripActivities->add($tripActivity);
            $tripActivity->setTrip($this);
        }

        return $this;
    }

    public function removeTripActivity(TripActivity $tripActivity): static
    {
        if ($this->TripActivities->removeElement($tripActivity)) {
            // set the owning side to null (unless already changed)
            if ($tripActivity->getTrip() === $this) {
                $tripActivity->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setTrip($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getTrip() === $this) {
                $expense->setTrip(null);
            }
        }

        return $this;
    }

    public function getParticipants(): Collection
    {
        return $this->tripUsers->map(function (TripUser $tripUser) {
            return $tripUser->getUser();
        });
    }

    // Optionnel : Méthode pour vérifier si un utilisateur est un participant
    public function isParticipant(User $user): bool
    {
        foreach ($this->tripUsers as $tripUser) {
            if ($tripUser->getUser() === $user) {
                return true;
            }
        }
        return false;
    }

    // Optionnel : Méthode pour ajouter un participant
    public function addParticipant(User $user): self
    {
        if (!$this->isParticipant($user)) {
            $tripUser = new TripUser();
            $tripUser->setUser($user);
            $tripUser->setTrip($this);
            $this->tripUsers->add($tripUser);
        }
        return $this;
    }

    // Optionnel : Méthode pour retirer un participant
    public function removeParticipant(User $user): self
    {
        $this->tripUsers->filter(function (TripUser $tripUser) use ($user) {
            return $tripUser->getUser() === $user;
        })->map(function (TripUser $tripUser) {
            $this->tripUsers->removeElement($tripUser);
            $tripUser->setTrip(null);
        });
        return $this;
    }
}



