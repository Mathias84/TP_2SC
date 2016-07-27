<?php

namespace MusiqueBundle\Form;

class Formulaire_album
{
  protected $artiste;
  protected $nom;
  
  public function getArtiste()
  {
    return $this->artiste;
  }
  
  public function setArtiste($unArtiste)
  {
    $this->artiste = $unArtiste;
  }
  
  public function getNom()
  {
    return $this->nom;
  }
  
  public function setNom($unNom)
  {
    $this->nom = $unNom;
  }
}
