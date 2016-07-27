<?php

namespace MusiqueBundle\Form;

class Formulaire_artiste
{
  protected $nom;
  protected $album;
  protected $titre;
  
  public function getNom()
  {
    return $this->nom;
  }
  
  public function setNom($unNom)
  {
    $this->nom = $unNom;
  }
}
