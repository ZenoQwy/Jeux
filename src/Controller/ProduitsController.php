<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;

class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'produit')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produits= $repoProduits->findAll();
        return $this->render('produits/pc/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produits-nintendo', name: 'produit-nintendo')]
    public function produitnintendo(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        
        $plateformeNintendo = $plateformeRepository->findOneBy(['libelle' => 'Nintendo']);
        
        if ($plateformeNintendo === null) {
            // Gérer le cas où la plateforme "Nintendo" n'existe pas
            throw $this->createNotFoundException('La plateforme "Nintendo" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeNintendo)
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/nintendo/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produits-pc', name: 'produit-pc')]
    public function produitpc(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformeNintendo = $plateformeRepository->findOneBy(['libelle' => 'Pc']);
        if ($plateformeNintendo === null) {
            // Gérer le cas où la plateforme "Pc" n'existe pas
            throw $this->createNotFoundException('La plateforme "Pc" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeNintendo)
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/pc/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produits-playstation', name: 'produit-playstation')]
    public function produitplaystation(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        
        $plateformeNintendo = $plateformeRepository->findOneBy(['libelle' => 'Playstation']);
        
        if ($plateformeNintendo === null) {
            // Gérer le cas où la plateforme "Playstation" n'existe pas
            throw $this->createNotFoundException('La plateforme "Playstation" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeNintendo)
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/playstation/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produits-xbox', name: 'produit-xbox')]
    public function produitxbox(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        
        $plateformeNintendo = $plateformeRepository->findOneBy(['libelle' => 'Xbox']);
        
        if ($plateformeNintendo === null) {
            // Gérer le cas où la plateforme "Xbox" n'existe pas
            throw $this->createNotFoundException('La plateforme "Xbox" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeNintendo)
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/xbox/index.html.twig', [
            'produits' => $produits
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
