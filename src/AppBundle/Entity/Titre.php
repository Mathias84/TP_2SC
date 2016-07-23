<?php

namespace MDBundle\Entity;

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
    * @ORM\ManyToMany(targetEntity="Album", mappeddBy="titres")
    */
  private $albums;
}
