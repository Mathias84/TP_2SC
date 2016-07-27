<?php
namespace MusiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
  * @ORM\Entity
  * @ORM\Table(name="album")
  */
class Album
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
    * @ORM\ManyToOne(targetEntity="Artiste", inversedBy="albums")
    * @ORM\JoinColumn(name="artiste_id", referencedColumnName="id")
    */
  private $artiste;
  
  /**
    * @ORM\OneToMany(targetEntity="Titre", mappedBy="album")
    */
  private $titres;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->titres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Album
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
     * Set artiste
     *
     * @param \MusiqueBundle\Entity\Artiste $artiste
     * @return Album
     */
    public function setArtiste(\MusiqueBundle\Entity\Artiste $artiste = null)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return \MusiqueBundle\Entity\Artiste 
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Add titres
     *
     * @param \MusiqueBundle\Entity\Titre $titres
     * @return Album
     */
    public function addTitre(\MusiqueBundle\Entity\Titre $titres)
    {
        $this->titres[] = $titres;

        return $this;
    }

    /**
     * Remove titres
     *
     * @param \MusiqueBundle\Entity\Titre $titres
     */
    public function removeTitre(\MusiqueBundle\Entity\Titre $titres)
    {
        $this->titres->removeElement($titres);
    }

    /**
     * Get titres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTitres()
    {
        return $this->titres;
    }
}
