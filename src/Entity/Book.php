<?php

namespace App\Entity;

use App\Entity\Tag;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api"})
     * @Assert\NotBlank(message="Le titre ne peut pas être vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "Le titre du livre doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le titre du livre doit faire au moins {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api"})
     * @Assert\NotBlank(message="Veuillez choisir un auteur")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Isbn(
     *     message = "Cette valeur n'est pas valide ISBN-10 ou ISBN-13."
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var array
     * 
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
     * @Groups({"api"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", fetch="EAGER", cascade={"persist"})
     * @Groups({"api"})
     */
    private $coverPicture;

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

     /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     *
     * @return mixed
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getCoverPicture(): ?Media
    {
        return $this->coverPicture;
    }

    public function setCoverPicture(?Media $coverPicture): self
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }
}
