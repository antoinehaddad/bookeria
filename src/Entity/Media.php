<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/** 
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 800,
     *     minHeight = 200,
     *     maxHeight = 800
     * )
    
    */

    private $name;

    /**
     * @Groups({"api"})
    */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    // TODO: create nfs /media => /public/uploads/
    // TODO: change url to => MEDIA_HOST.$this->name;
    /**
     * Get the full url of the media
     */ 
    public function getUrl()
    {
        return "/public/uploads/covers/".$this->name;
    }

    /**
    * For security reason :  
    * It is not possible to put a default value on input file
    * Because it is for user to select file
    * This is why we add this property to transport the file name when editing form
     */ 
    private $nameBag;
    public function getNameBag()
    {
        return $this->nameBag;
    }

    public function setNameBag($nameBag)
    {
        $this->nameBag = $nameBag;

        return $this;
    }
}
