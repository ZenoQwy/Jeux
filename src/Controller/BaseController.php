<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Support;
use App\Entity\Avis;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        
        $les5produits = [];
        $produits_count = count($produits);
        
        if ($produits_count > 0) {
            $indexes = array_rand($produits, min(5, $produits_count));
            foreach ($indexes as $index) {
                $les5produits[] = $produits[$index];
            }
        }



        $avis = $entityManagerInterface->getRepository(Avis::class)->findAll();
        $les3avis = [];
        $avis_count = count($avis);
        if ($avis_count > 0) {
            $indexess = array_rand($avis, min(3, $avis_count));
            foreach ($indexess as $index) {
                $les3avis[] = $avis[$index];
            }
        }
        
        return $this->render('base/index.html.twig', [
            'produits' => $les5produits,
            'avis' => $les3avis
        ]);

    }

    #[Route('/private-votre-compte', name: 'compte')]
    public function compte(): Response
    {
        return $this->render('base/compte.html.twig', [
        ]);
    }

    #[Route('/politique-de-confidentialité', name: 'confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('base/confidentialite.html.twig', [
        ]);
    }

    #[Route('/mentions-légales', name: 'ml')]
    public function ml(): Response
    {
        return $this->render('base/ml.html.twig', [
        ]);
    }

    #[Route('/conditions-générales-de-vente', name: 'conditions')]
    public function condition(): Response
    {
        return $this->render('base/generaledevente.html.twig', [
        ]);
    }

}
