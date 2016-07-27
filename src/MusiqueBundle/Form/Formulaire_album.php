<?php

namespace MusiqueBundle\Form;

class Formulaire
{
  protected $artiste;
  protected $album;
  protected $titre;
  
  public function getArtiste()
  {
    return $this->artiste;
  }
  
  public function setArtiste($unArtiste)
  {
    $this->artiste = $unArtiste;
  }
  
  public function getAlbum()
  {
    return $this->album;
  }
  
  public function setAlbum($unAlbum)
  {
    $this->album = $unAlbum;
  }
  
  public function getTitre()
  {
    return $this->titre;
  }
  
  public function setTitre($unTitre)
  {
    $this->titre = $unTitre;
  }
}
