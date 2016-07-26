<?php

namespace MusiqueBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MusiqueBundle\Form\Formulaire;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
      $name = 'Bob';
      
      $formulaire = new Formulaire();
      
      $form = $this->createFormBuilder($formulaire)
        ->add('artiste', 'text')
        ->add('album', 'text')
        ->add('titre', 'text')
        ->add('save', 'submit')
        ->getForm();
      
      return $this->render('MusiqueBundle:Default:index.html.twig', array(
        'name' => $name,
        'form' => $form->createView(),
      ));
    }
}
