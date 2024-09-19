<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column(nullable: true)]
    private ?int $budget = null;

    #[Vich\UploadableField(mapping: 'avatars', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\OneToMany(targetEntity: TripUser::class, mappedBy: 'trip', cascade: ['persist', 'remove'])]
    private Collection $tripUsers;

    #[ORM\OneToMany(targetEntity: TripActivity::class, mappedBy: 'trip', cascade: ['persist', 'remove'])]
    private Collection $tripActivities;

    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'trip', cascade: ['persist', 'remove'])]
    private Collection $expenses;

    #[ORM\OneToMany(targetEntity: TripInvitation::class, mappedBy: 'trip', cascade: ['persist', 'remove'])]
    private Collection $invitations;

    public function __construct()
    {
        $this->tripUsers = new ArrayCollection();
        $this->tripActivities = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    // Getters and Setters...

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

    #[ORM\PrePersist]
    public function setCreationDateValue(): void
    {
        $this->creationDate = new \DateTime();
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): static
    {
        $this->imageSize = $imageSize;

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
        return $this->tripActivities;
    }

    public function addTripActivity(TripActivity $tripActivity): static
    {
        if (!$this->tripActivities->contains($tripActivity)) {
            $this->tripActivities->add($tripActivity);
            $tripActivity->setTrip($this);
        }

        return $this;
    }

    public function removeTripActivity(TripActivity $tripActivity): static
    {
        if ($this->tripActivities->removeElement($tripActivity)) {
            if ($tripActivity->getTrip() === $this) {
                $tripActivity->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Expense>
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
            if ($expense->getTrip() === $this) {
                $expense->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TripInvitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(TripInvitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setTrip($this);
        }

        return $this;
    }

    public function removeInvitation(TripInvitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            if ($invitation->getTrip() === $this) {
                $invitation->setTrip(null);
            }
        }

        return $this;
    }
}
