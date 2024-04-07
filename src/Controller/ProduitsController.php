<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProduitsController extends AbstractController
{
    #[Route('/produits/{designation}', name: 'produit')]
    public function index(string $designation, EntityManagerInterface $entityManagerInterface, HttpClientInterface $httpClient): Response
    {
        return $this->render('produits/index.html.twig', [
            'designation' => $designation
        ]);
    }

    //composer require synfony/asset
    #[Route('/produit/{id}-{designation}', name: 'leproduit')]
    public function leproduit(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $id = $request->get('id');
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produit = $repoProduits->find($id);

        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformes = $produit->getPlateformes();
        return $this->render('produits/leproduit.html.twig', [
            'produit' => $produit,
            'plateformes' => $plateformes
        ]);
    }

}
