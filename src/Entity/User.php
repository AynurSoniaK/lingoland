<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\httpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé")
 * @UniqueEntity(fields={"name"}, message="Ce nom est déjà utilisé")
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     * @Assert\Range(
     *  min = 18,
     *  max = 99,
     *  notInRangeMessage = "Vous devez avoir au moins {{ min }} ans et probablement moins de {{ max }} ans ")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     */
    private $city;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     * @Assert\Length(
     *      min = 150,
     *      max = 1000,
     *      minMessage = "Votre présentation doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre présentation ne doit pas faire plus de {{ limit }} caractères."
     * )
     */
    private $introduction;

    /**
     * @ORM\ManyToMany(targetEntity=Availibility::class, inversedBy="users")
     * @Assert\NotNull(message = "Ce champ ne peut pas être vide")
     */
    private $availibility;

    /**
     * @ORM\ManyToMany(targetEntity=Communication::class, inversedBy="users",)
     */
    private $communication;

    /**
     * @ORM\ManyToOne(targetEntity=Frequency::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $frequency;

    /**
     * @ORM\ManyToMany(targetEntity=Hobbies::class, inversedBy="users")
     * @Assert\NotBlank
     */
    private $hobby;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="users")
     * @Assert\NotBlank
     */
    private $language;

    /**
     * @ORM\ManyToMany(targetEntity=LanguageLearned::class, inversedBy="users")
     * @Assert\NotBlank
     */
    private $languageLearned;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $photo;



    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="recipient", orphanRemoval=true)
     */
    private $received;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     */
    private $genre;


    public function __construct()
    {
        $this->availibility = new ArrayCollection();
        $this->communication = new ArrayCollection();
        $this->hobby = new ArrayCollection();
        $this->language = new ArrayCollection();
        $this->languageLearned = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * @return Collection|Availibility[]
     */
    public function getAvailibility(): Collection
    {
        return $this->availibility;
    }

    public function addAvailibility(Availibility $availibility): self
    {
        if (!$this->availibility->contains($availibility)) {
            $this->availibility[] = $availibility;
        }

        return $this;
    }

    public function removeAvailibility(Availibility $availibility): self
    {
        $this->availibility->removeElement($availibility);

        return $this;
    }

    /**
     * @return Collection|Communication[]
     */
    public function getCommunication(): Collection
    {
        return $this->communication;
    }

    public function addCommunication(Communication $communication): self
    {
        if (!$this->communication->contains($communication)) {
            $this->communication[] = $communication;
        }

        return $this;
    }

    public function removeCommunication(Communication $communication): self
    {
        $this->communication->removeElement($communication);

        return $this;
    }

    public function getFrequency(): ?Frequency
    {
        return $this->frequency;
    }

    public function setFrequency(?Frequency $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @return Collection|Hobbies[]
     */
    public function getHobby(): Collection
    {
        return $this->hobby;
    }

    public function addHobby(Hobbies $hobby): self
    {
        if (!$this->hobby->contains($hobby)) {
            $this->hobby[] = $hobby;
        }

        return $this;
    }

    public function removeHobby(Hobbies $hobby): self
    {
        $this->hobby->removeElement($hobby);

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->language->removeElement($language);

        return $this;
    }

    /**
     * @return Collection|LanguageLearned[]
     */
    public function getLanguageLearned(): Collection
    {
        return $this->languageLearned;
    }

    public function addLanguageLearned(LanguageLearned $languageLearned): self
    {
        if (!$this->languageLearned->contains($languageLearned)) {
            $this->languageLearned[] = $languageLearned;
        }

        return $this;
    }

    public function removeLanguageLearned(LanguageLearned $languageLearned): self
    {
        $this->languageLearned->removeElement($languageLearned);

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    /**
     * @return Collection|Messages[]
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Messages $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Messages $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Messages $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setRecipient($this);
        }

        return $this;
    }

    public function removeReceived(Messages $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getRecipient() === $this) {
                $received->setRecipient(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?genre
    {
        return $this->genre;
    }

    public function setGenre(?genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

}
