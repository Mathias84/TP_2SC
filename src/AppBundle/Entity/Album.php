<?php

namespace MDBundle\Entity;

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
    * @ORM\ManyToMany(targetEntity="Artiste", mappeddBy="albums")
    */
  private $artistes;
  
  /**
    * @ORM\ManyToMany(targetEntity="Titre", inversedBy="albums")
    * @ORM\JoinTable(name="albums_titres")
    */
  private $titres;
}
