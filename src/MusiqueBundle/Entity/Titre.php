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
    * @ORM\ManyToMany(targetEntity="Album", mappedBy="titres")
    */
  private $albums;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add albums
     *
     * @param \MusiqueBundle\Entity\Album $albums
     * @return Titre
     */
    public function addAlbum(\MusiqueBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;

        return $this;
    }

    /**
     * Remove albums
     *
     * @param \MusiqueBundle\Entity\Album $albums
     */
    public function removeAlbum(\MusiqueBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }
}
