<?php

namespace App\Entity;

use App\Repository\CategoryActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryActivityRepository::class)]
class CategoryActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $nom = null;

    /**
     * @var Collection<int, activity>
     */
    #[ORM\OneToMany(targetEntity: activity::class, mappedBy: 'categoryActivity')]
    private Collection $activities;

    #[ORM\Column(length: 255)]
    private ?string $backgroundColor = null;


    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setCategoryActivity($this);
        }

        return $this;
    }

    public function removeActivity(activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getCategoryActivity() === $this) {
                $activity->setCategoryActivity(null);
            }
        }

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }


}
