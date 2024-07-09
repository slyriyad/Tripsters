<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, TripUser>
     */
    #[ORM\OneToMany(targetEntity: TripUser::class, mappedBy: 'user')]
    private Collection $tripUsers;

    /**
     * @var Collection<int, ExpenseSplit>
     */
    #[ORM\OneToMany(targetEntity: ExpenseSplit::class, mappedBy: 'user')]
    private Collection $expenseSplits;

    public function __construct()
    {
        $this->tripUsers = new ArrayCollection();
        $this->expenseSplits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $tripUser->setUser($this);
        }

        return $this;
    }

    public function removeTripUser(TripUser $tripUser): static
    {
        if ($this->tripUsers->removeElement($tripUser)) {
            // set the owning side to null (unless already changed)
            if ($tripUser->getUser() === $this) {
                $tripUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExpenseSplit>
     */
    public function getExpenseSplits(): Collection
    {
        return $this->expenseSplits;
    }

    public function addExpenseSplit(ExpenseSplit $expenseSplit): static
    {
        if (!$this->expenseSplits->contains($expenseSplit)) {
            $this->expenseSplits->add($expenseSplit);
            $expenseSplit->setUser($this);
        }

        return $this;
    }

    public function removeExpenseSplit(ExpenseSplit $expenseSplit): static
    {
        if ($this->expenseSplits->removeElement($expenseSplit)) {
            // set the owning side to null (unless already changed)
            if ($expenseSplit->getUser() === $this) {
                $expenseSplit->setUser(null);
            }
        }

        return $this;
    }
}
