<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmpruntRepository;
use App\Entity\Emprunt;

class EmpruntController extends AbstractController
{
    /**
     * @Route("/admin/emprunt", name="emprunt")
     */
    public function index(EmpruntRepository $empruntRepository): Response
    {
        $liste_emprunts = $empruntRepository->findAll();
        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $liste_emprunts
        ]);
    }
}
