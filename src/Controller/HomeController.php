<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER", message="No tiene permisos para realizar esta acción.")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index()
    {
        return $this->redirectToRoute('client_index');
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
    }
}
