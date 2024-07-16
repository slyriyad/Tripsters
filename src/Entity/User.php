<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;
#[ORM\Column(length: 180, unique: true)]
private ?string $email = null;

#[ORM\Column]
private array $roles = [];

#[ORM\Column]
private ?string $password = null;

#[Vich\UploadableField(mapping: 'avatars', fileNameProperty: 'imageName', size: 'imageSize')]
private ?File $imageFile = null;

#[ORM\Column(nullable: true)]
private ?string $imageName = null;

#[ORM\Column(type: 'integer', nullable: true)]
private ?int $imageSize = null;

#[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $updatedAt = null;

/**
 * @var Collection<int, Activity>
 */
#[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'createdBy')]
private Collection $activities;

public function __construct()
{
    $this->activities = new ArrayCollection();
}

public function getId(): ?int
{
    return $this->id;
}

public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(string $email): self
{
    $this->email = $email;
    return $this;
}

public function getUserIdentifier(): string
{
    return (string) $this->email;
}

public function getRoles(): array
{
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';
    return array_unique($roles);
}

public function setRoles(array $roles): self
{
    $this->roles = $roles;
    return $this;
}

public function getPassword(): string
{
    return $this->password;
}

public function setPassword(string $password): self
{
    $this->password = $password;
    return $this;
}

public function eraseCredentials(): void
{
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
}

public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;

    if (null !== $imageFile) {
        $this->updatedAt = new \DateTimeImmutable();
    }
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}

public function setImageName(?string $imageName): void
{
    $this->imageName = $imageName;
}

public function getImageName(): ?string
{
    return $this->imageName;
}

public function setImageSize(?int $imageSize): void
{
    $this->imageSize = $imageSize;
}

public function getImageSize(): ?int
{
    return $this->imageSize;
}

/**
 * @return Collection<int, Activity>
 */
public function getActivities(): Collection
{
    return $this->activities;
}

public function addActivity(Activity $activity): static
{
    if (!$this->activities->contains($activity)) {
        $this->activities->add($activity);
        $activity->setCreatedBy($this);
    }

    return $this;
}

public function removeActivity(Activity $activity): static
{
    if ($this->activities->removeElement($activity)) {
        // set the owning side to null (unless already changed)
        if ($activity->getCreatedBy() === $this) {
            $activity->setCreatedBy(null);
        }
    }

    return $this;
}

public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->imageName,
            $this->imageSize,
            $this->updatedAt,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->imageName,
            $this->imageSize,
            $this->updatedAt
        ) = unserialize($serialized);
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'password' => $this->password,
            'imageName' => $this->imageName,
            'imageSize' => $this->imageSize,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->roles = $data['roles'];
        $this->password = $data['password'];
        $this->imageName = $data['imageName'];
        $this->imageSize = $data['imageSize'];
        $this->updatedAt = $data['updatedAt'];
    }
}