<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelRepository::class)
 */
class Level
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Language::class, mappedBy="level")
     */
    private $languages;

    /**
     * @ORM\OneToMany(targetEntity=LanguageLearned::class, mappedBy="level")
     */
    private $languageLearneds;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->languageLearneds = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
            $language->setLevel($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getLevel() === $this) {
                $language->setLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LanguageLearned[]
     */
    public function getLanguageLearneds(): Collection
    {
        return $this->languageLearneds;
    }

    public function addLanguageLearned(LanguageLearned $languageLearned): self
    {
        if (!$this->languageLearneds->contains($languageLearned)) {
            $this->languageLearneds[] = $languageLearned;
            $languageLearned->setLevel($this);
        }

        return $this;
    }

    public function removeLanguageLearned(LanguageLearned $languageLearned): self
    {
        if ($this->languageLearneds->removeElement($languageLearned)) {
            // set the owning side to null (unless already changed)
            if ($languageLearned->getLevel() === $this) {
                $languageLearned->setLevel(null);
            }
        }

        return $this;
    }


}
