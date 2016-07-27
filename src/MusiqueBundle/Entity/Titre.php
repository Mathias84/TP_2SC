<?php
namespace MusiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
  * @ORM\Entity
  * @ORM\Table(name="titre")
  */
class Titre
{
  /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
  private $id;
  
  /**
    * @ORM\Column(type="string", length=50)
    */
  private $nom;
  
  /**
    * @ORM\ManyToOne(targetEntity="Album", inversedBy="titres")
    * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
    */
  private $album;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Titre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set album
     *
     * @param \MusiqueBundle\Entity\Album $album
     * @return Titre
     */
    public function setAlbum(\MusiqueBundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \MusiqueBundle\Entity\Album 
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
