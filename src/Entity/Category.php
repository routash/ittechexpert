<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $added_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Programs::class, mappedBy="category")
     */
    private $programs;

    public function __construct()
    {
        $this->added_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->programs = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->added_at;
    }

    public function setAddedAt(\DateTimeInterface $added_at): self
    {
        $this->added_at = $added_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Programs[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Programs $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setCategory($this);
        }

        return $this;
    }

    public function removeProgram(Programs $program): self
    {
        if ($this->programs->contains($program)) {
            $this->programs->removeElement($program);
            // set the owning side to null (unless already changed)
            if ($program->getCategory() === $this) {
                $program->setCategory(null);
            }
        }

        return $this;
    }
}
