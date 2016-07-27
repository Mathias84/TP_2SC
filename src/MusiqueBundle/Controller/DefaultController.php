<?php

namespace MusiqueBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;

//Chargement des entités
use MusiqueBundle\Entity\Artiste;
use MusiqueBundle\Entity\Album;
use MusiqueBundle\Entity\Titre;

//Chargement des types de formulaire
use MusiqueBundle\Form\Formulaire_artiste;
use MusiqueBundle\Form\Formulaire_album;
use MusiqueBundle\Form\Formulaire_titre;


class DefaultController extends Controller
{
  /**
   * @Route("/", name="accueil")
   */
  public function indexAction()
  {
    //Récupération des artistes dans la BDD
    $lesArtistes = $this->getDoctrine()
      ->getRepository('MusiqueBundle:Artiste')
      ->findBy(array(), array('nom' => 'ASC'));
    
    return $this->render('MusiqueBundle:Default:index.html.twig', array(
      'lesArtistes' => $lesArtistes,
    ));
  }
    
  /**
   * @Route("/admin/ajouter_un_artiste", name="ajouter_un_artiste")
   */
  public function ajouterUnArtisteAction(Request $request)
  {
    $titre = "Ajout d'un artiste";
    
    $formulaire = new Formulaire_artiste();
    
    $form = $this->createFormBuilder($formulaire)
      ->add('nom', 'text', array('label' => 'Nom de l\'artiste :'))
      ->add('save', 'submit', array('label' => 'Ajouter'))
      ->getForm();
      
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid())
    {
      $nom = $form->get('nom')->getData();
      
      //Si l'artiste a bien été ajouté dans la BDD
      if ($this->ajouterunArtiste($nom))
      {
        $this->addFlash(
          'notice',
          'L\'artiste "' . $nom . '" a bien été ajouté!'
        );
      }
      else
      {
        $this->addFlash(
          'error',
          'Erreur, l\'artiste "' . $nom . '" existe déjà!'
        );
      }
      
      return $this->redirectToRoute('accueil');
    }
    
    return $this->render('MusiqueBundle:Default:formulaire.html.twig', array(
      'titre' => $titre,
      'form'  => $form->createView(),
    ));
  }
  
  /**
   * @Route("/modifier_un_artiste/{id}", name="modifier_un_artiste")
   */
  public function modifierUnArtisteAction($id, Request $request)
  {
    $titre = "Modification d'un artiste";
    
    //Récupération de l'artiste à modifier
    $lArtiste = $this->getArtiste($id);
    
    $nomActuel = $lArtiste->getNom();
    
    $formulaire = new Formulaire_artiste();
    $formulaire->setNom($nomActuel);
    
    $form = $this->createFormBuilder($formulaire)
      ->add('nom', 'text', array('label' => 'Nom de l\'artiste :'))
      ->add('save', 'submit', array('label' => 'Modifier'))
      ->getForm();
    
    //Vérifie l'état du formulaire
    $form->handleRequest($request);
    
    //Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid())
    {
      //Récupération du nouveau nom de l'artiste renseigné par l'utilisateur
      $nouveauNom = $form['nom']->getData();
      
      //Si l'artiste a bien été modifié dans la BDD
      if ($this->modifierUnArtiste($id, $nouveauNom))
      {
        $this->addFlash(
          'notice',
          'L\'artiste a bien été modifié!'
        );
        
        $this->addFlash(
          'notice',
          '(vous venez de renommer "' . $nomActuel . '" en "' . $nouveauNom . '")'
        );
      }
      else
      {
        $this->addFlash(
          'error',
          'Erreur, le nom d\'artiste "' . $nouveauNom . '" est déjà utilisé!'
        );
      }
      
      return $this->redirectToRoute('accueil');
    }
    
    return $this->render('MusiqueBundle:Default:formulaire.html.twig', array(
      'titre' => $titre,
      'form'  => $form->createView(),
    ));
  }
  
  /**
   * @Route("/supprimer_un_artiste/{id}", name="supprimer_un_artiste")
   */
  public function supprimerUnArtisteAction($id)
  {
    //Récupération de l'artiste à modifier
    $lArtiste = $this->getArtiste($id);
    
    $nom = $lArtiste->getNom();
    
    // Appel de la fonction pour supprimer un artiste et ses dépendances dans la BDD
    $this->supprimerUnArtiste($id);
    
    $this->addFlash(
      'notice',
      'L\'artiste "' . $nom . '" a bien été supprimé!'
    );
    
    $this->addFlash(
      'notice',
      '(ainsi que ses albums éventuellement associés)'
    );
    
    return $this->redirectToRoute('accueil');
  }
  
  /**
   * @Route("/ajouter_un_album", name="ajouter_un_album")
   */
  public function ajouterUnAlbumAction(Request $request)
  {
    $titre = "Ajout d'un album";
    
    $formulaire_album = new Formulaire_album();
    
    $form = $this->createFormBuilder($formulaire_album)
      ->add('artiste', 'entity', array(
        'class' => 'MusiqueBundle:Artiste',
        'label' => 'Artistes :',
        'choice_label' => 'nom',
        'placeholder' => 'Veuillez selectionner un artiste...',
        'query_builder' => function (EntityRepository $er)
        {
          return $er->createQueryBuilder('artiste')
            ->orderBy('artiste.nom', 'ASC');
        },
      ))
      ->add('nom', 'text', array('label' => 'nom de l\'album :',))
      ->add('save', 'submit', array('label' => 'Ajouter'))
      ->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid())
    {
      $artiste  = $form['artiste']->getData();
      $nomAlbum = $form['nom']->getData();
      
      $nomArtiste = $artiste->getNom();
      
      //Si l'album a bien été ajouté dans la BDD
      if ($this->ajouterunAlbum($artiste, $nomAlbum))
      {
        $this->addFlash(
          'notice',
          'L\'album "' . $nomAlbum . '" pour l\'artiste "' . $nomArtiste . '" a bien été ajouté!'
        );
      }
      else
      {
        $this->addFlash(
          'error',
          'Erreur, l\'album "' . $nomAlbum . '" pour l\'artiste "' . $nomArtiste . '" existe déjà!'
        );
      }
      
      return $this->redirectToRoute('accueil');
    }
    
    return $this->render('MusiqueBundle:Default:formulaire.html.twig', array(
      'titre' => $titre,
      'form'  => $form->createView(),
    ));
  }
  
  /**
   * @Route("/modifier_un_album/{id}", name="modifier_un_album")
   */
  public function modifierUnAlbumAction($id, Request $request)
  {
    //Récupération de l'album dans la BDD
    $lAlbum = $this->getAlbum($id);
    
    //Récupération de l'artiste qui a créé l'album dans la BDD
    $lArtiste = $lAlbum->getArtiste();
    
    $titre = 'Modification de l\'album "' . $lAlbum->getNom() . '" de "' . $lArtiste->getNom() . '".';
    
    // $leServiceChoisi = $this->getDoctrine() /* A voir doctrine OneToOne peut-être ********************************************************/ 
      // ->getRepository('CGFRendezvousBundle:Service')
      // ->findOneByNom($laReservation->getService());
    
    // $lesServicesDeLaTournee = $laTournee->getServices();
    
    $formulaire_album = new Formulaire_album();
    $formulaire_album->setArtiste($lArtiste);
    $formulaire_album->setNom($lAlbum->getNom());
    
    // if ($leServiceChoisi)
    // {
      // $formulaire->setService($leServiceChoisi);
    // }
    
    //Récupération des services proposés lors de la tournée consulaire
    // $tourneeServices = $laTournee->getServices();
    
    $form = $this->createFormBuilder($formulaire_album)
      ->add('artiste', 'entity', array(
        'class' => 'MusiqueBundle:Artiste',
        'label' => 'Artiste :',
        'choice_label' => 'nom',
        // 'choices' => $laTournee->getServices(),
      ))
      ->add('nom', 'text', array('label' => 'Nom :'))
      ->add('save', 'submit', array('label' => 'Modifier'))
      ->getForm();
      
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid())
    {
      //Récupération des valeurs renseignées par l'utilisateur
      $nouvelArtiste = $form['artiste']->getData();
      $nouveauNom = $form['nom']->getData();
      
      //Si l'album a bien été modifié dans la BDD
      if ($this->modifierUnAlbum($id, $nouvelArtiste, $nouveauNom))
      {
        $this->addFlash(
          'notice',
          'L\'album a bien été modifié!'
        );
      }
      else
      {
        $this->addFlash(
          'error',
          'Erreur, l\'album n\'a pas été modifié!'
        );
      }
      
      return $this->redirectToRoute('accueil');
    }
    
    return $this->render('MusiqueBundle:Default:formulaire.html.twig', array(
      'titre' => $titre,
      'form'  => $form->createView(),
    ));
  }
  
  /**
   * @Route("/supprimer_un_album/{id}", name="supprimer_un_album")
   */
  public function supprimerUnAlbumAction($id)
  {
    //Récupération de l'album à supprimer
    $lAlbum = $this->getAlbum($id);
    
    $nomDeLArtiste = $lAlbum->GetArtiste()->getNom();
    $nomAlbum = $lAlbum->getNom();
    
    //Appel de la fonction pour supprimer un album et ses titres dans la BDD
    $this->supprimerUnAlbum($id);
    
    $this->addFlash(
      'notice',
      'L\'album "' . $nomAlbum . '" de "' . $nomDeLArtiste . '" a bien été supprimé!'
    );
    
    $this->addFlash(
      'notice',
      '(ainsi que ses titres éventuellement associés)'
    );
    
    return $this->redirectToRoute('accueil');
  }
  
  /* Fonctions **************************************************************************************************************************************************************/
  
  //Fonction permettant de récupérer un artiste
  //$unIdArtiste : l'identifiant de l'artiste à récupérer
  function getArtiste($unIdArtiste)
  {
    $artiste = $this->getDoctrine()
      ->getRepository('MusiqueBundle:Artiste')
      ->find($unIdArtiste);
    
    if (!$artiste)
    {
      throw $this->createNotFoundException('Pas d\'artiste trouvé pour l\'identifiant' . $unIdArtiste);
    }
    
    return $artiste;
  }
  
  //Fonction permettant de récupérer un album
  //$unIdAlbum : l'identifiant de l'album à récupérer
  function getAlbum($unIdAlbum)
  {
    $album = $this->getDoctrine()
      ->getRepository('MusiqueBundle:Album')
      ->find($unIdAlbum);
    
    if (!$album)
    {
      throw $this->createNotFoundException('Pas d\'album trouvé pour l\'identifiant' . $unIdAlbum);
    }
    
    return $album;
  }
  
  //Fonction permettant d'ajouter un artiste
  //$unNom : le nom de l'artiste à ajouter
  function ajouterUnArtiste($unNom)
  {
    $query = $this->getDoctrine()
      ->getRepository('MusiqueBundle:Artiste')
      ->createQueryBuilder('artiste')
        ->where('artiste.nom = :nom')
        ->setParameter('nom', $unNom)
        ->getQuery();
    
    $artiste = $query->getResult();
    
    //Si l'artiste existe déjà
    if ($artiste)
    {
      return false;
    }
    else
    {
      $artiste = new Artiste();
      
      $artiste->setNom($unNom);
      
      $em = $this->getDoctrine()->getManager();    
      $em->persist($artiste);
      $em->flush();
      
      return true;
    }
  }
  
  //Fonction permettant de modifier un artiste
  //$unId : l'identifiant de l'artiste à modifier
  //$unNom : le nouveau nom de l'artiste
  function modifierUnArtiste($unId, $unNom)
  {
    $query = $this->getDoctrine()
      ->getRepository('MusiqueBundle:Artiste')
      ->createQueryBuilder('artiste')
        ->where('artiste.nom = :nom')
        ->setParameter('nom', $unNom)
        ->getQuery();
    
    $artiste = $query->getResult();
    
    //Si l'artiste existe déjà (si le nouveau nom est déjà utilisé)
    if ($artiste)
    {
      return false;
    }
    else
    {
      $em = $this->getDoctrine()->getManager();
    
      $artiste = $em->getRepository('MusiqueBundle:Artiste')->find($unId);
      
      $artiste->setNom($unNom);
      
      $em->flush();
      
      return true;
    }
  }
  
  //Fonction permettant de supprimer un artiste
  //$unId : l'identifiant de l'artiste à supprimer
  function supprimerUnArtiste($unId)
  {
    $em = $this->getDoctrine()->getManager();
    
    // Récupération de l'artiste
    $artiste = $em->getRepository('MusiqueBundle:Artiste')->find($unId);
    
    // Récupération des albums del'artiste à supprimer
    $sesAlbums = $artiste->getAlbums();
    
    // S'il existe des albums fait par l'artiste à supprimer
    if ($sesAlbums)
    {
      // Parcours de ces albums et suppression de ceux-ci
      foreach ($sesAlbums as &$album)
      {
        $this->supprimerUnAlbum($album->getId());
      }
    }
    
    $em->remove($artiste);
    
    $em->flush();
  }
  
  //Fonction permettant d'ajouter un album
  //paramètres : les paramètres de la tournée consulaire à ajouter
  function ajouterUnAlbum($unArtiste, $unNomDAlbum)
  {
    // Parcours des albums de l'artiste pour vérifier si le nom d'album n'est pas déjà utilisé
    foreach ($unArtiste->getAlbums() as &$album)
    {
      if ($album->getNom() == $unNomDAlbum)
      {
        return false;
      }
    }
    
    $album = new Album();
    $album->setNom($unNomDAlbum);
    $album->setArtiste($unArtiste);
    
    // $unArtiste->addAlbum($album);
    
    $em = $this->getDoctrine()->getManager();    
    $em->persist($album);
    $em->flush();
    
    return true;
  }
  
  //Fonction permettant de modifier un album
  //$unId : l'identifiant de l'album à modifier
  //$unNouvelArtiste : le nouvel artiste de l'album à modifier
  //$unNouveauNom : le nouveau nom de l'album à modifier
  function modifierUnAlbum($unId, $unNouvelArtiste, $unNouveauNom)
  {
    $em = $this->getDoctrine()->getManager();
  
    $album = $em->getRepository('MusiqueBundle:Album')->find($unId);
    
    $album->setArtiste($unNouvelArtiste);
    $album->setNom($unNouveauNom);
    
    $em->flush();
    
    return true;
  }
  
  //Fonction permettant de supprimer un album
  //$unId : l'identifiant de l'album à supprimer
  function supprimerUnAlbum($unId)
  {
    $em = $this->getDoctrine()->getManager();
    
    $album = $em->getRepository('MusiqueBundle:Album')->find($unId);
    // $album = $this->getDoctrine()->getRepository('MusiqueBundle:Album')->find($unId);
    
    // $lArtiste = $album->getArtiste();
    $lesTitres = $album->getTitres();
    
    // $lArtiste->removeAlbum($album);
    
    if ($lesTitres)
    {
      foreach($lesTitres as &$titre)
      {
        $em->remove($titre);
      }
    }
    
    $em->remove($album);
    
    $em->flush();
  }
}