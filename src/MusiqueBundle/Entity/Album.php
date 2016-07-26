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
    * @ORM\ManyToMany(targetEntity="Artiste", mappedBy="albums")
    */
  private $artistes;
  
  /**
    * @ORM\ManyToMany(targetEntity="Titre", inversedBy="albums")
    * @ORM\JoinTable(name="albums_titres")
    */
  private $titres;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->artistes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add artistes
     *
     * @param \MusiqueBundle\Entity\Artiste $artistes
     * @return Album
     */
    public function addArtiste(\MusiqueBundle\Entity\Artiste $artistes)
    {
        $this->artistes[] = $artistes;

        return $this;
    }

    /**
     * Remove artistes
     *
     * @param \MusiqueBundle\Entity\Artiste $artistes
     */
    public function removeArtiste(\MusiqueBundle\Entity\Artiste $artistes)
    {
        $this->artistes->removeElement($artistes);
    }

    /**
     * Get artistes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtistes()
    {
        return $this->artistes;
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
