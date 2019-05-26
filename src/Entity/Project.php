<?php

namespace App\Entity;

use App\Entity\Tag;
use Doctrine\ORM\Mapping as ORM;
use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project implements TaggableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageProject", mappedBy="project", fetch="EAGER")
     */
    private $images;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cratedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ProjectCreatedBy")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastEditBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="projects")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tagsText;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @return Collection|ImageProject[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }
    /**
     * Set the value of images
     *
     * @return  self
     */ 
    public function setImage($images)
    {
        $this->images = $images;

        return $this;
    }
    public function addImage(ImageProject $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setImage($this);
        }

        return $this;
    }

    public function removeImage(ImageProject $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getImage() === $this) {
                $image->setImage(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->title;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCratedAt(): ?\DateTimeInterface
    {
        return $this->cratedAt;
    }

    public function setCratedAt(\DateTimeInterface $cratedAt): self
    {
        $this->cratedAt = $cratedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastEditBy(): ?string
    {
        return $this->lastEditBy;
    }

    public function setLastEditBy(?string $lastEditBy): self
    {
        $this->lastEditBy = $lastEditBy;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): iterable
    {
        return $this->tags;
    }

    public function addTag(TagInterface $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(TagInterface $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function hasTag(TagInterface $tag): bool
    {
        return $this->tags->contains($tag);
    }

    public function getTagNames(): array
    {
        return empty($this->tagsText) ? [] : \array_map('trim', explode(',', $this->tagsText));
    }

    public function getTagsText(): ?string
    {
        $this->tagsText = \implode(', ', $this->tags->toArray());

        return $this->tagsText;
    }

    public function setTagsText(string $tagsText): self
    {
        $this->tagsText = $tagsText;
        $this->updated = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
