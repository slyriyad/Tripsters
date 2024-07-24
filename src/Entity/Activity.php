<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?CategoryActivity $categoryActivity = null;

    /**
     * @var Collection<int, TripActivity>
     */
    #[ORM\OneToMany(targetEntity: TripActivity::class, mappedBy: 'activity')]
    private Collection $tripActivities;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'activity')]
    private Collection $comments;

    public function __construct()
    {
        $this->tripActivities = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCategoryActivity(): ?CategoryActivity
    {
        return $this->categoryActivity;
    }

    public function setCategoryActivity(?CategoryActivity $categoryActivity): static
    {
        $this->categoryActivity = $categoryActivity;

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
            $tripActivity->setActivity($this);
        }

        return $this;
    }

    public function removeTripActivity(TripActivity $tripActivity): static
    {
        if ($this->tripActivities->removeElement($tripActivity)) {
            // set the owning side to null (unless already changed)
            if ($tripActivity->getActivity() === $this) {
                $tripActivity->setActivity(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setActivity($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getActivity() === $this) {
                $comment->setActivity(null);
            }
        }

        return $this;
    }
}
