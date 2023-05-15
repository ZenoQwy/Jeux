<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;

class PcController extends AbstractController
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                    //
    // FONCTIONS CONCERNANTS LES RECHERCHES DES PRODUITS DE LA PLATEFORME "Pc" POUR LES VISITEURS DU SITE //
    //                                                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////////////////////


    // Recherche par mot les produits de la plateforme Pc dans le panel standard de consultation des produits
    #[Route('/search-pc', name: 'search-pc')]
    public function search(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePc = $plateformeRepository->findOneBy(['libelle' => 'Pc']);
        if ($plateformePc === null) {
            // Gérer le cas où la plateforme "Pc" n'existe pas
            throw $this->createNotFoundException('La plateforme "Pc" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePc)
            ->getQuery();
        
        $produits = $query->getResult();

        $nomProduit = $request->query->get('search-input');   
        if( $nomProduit == ""){
            return $this->redirectToRoute('produit-pc');
        }else{
            $query = $entityManagerInterface->createQuery(
                'SELECT p FROM App\Entity\Produit p JOIN p.plateformes c WHERE p.designation LIKE :nomProduit AND c.libelle = :plateformes'
            )
            ->setParameter('nomProduit', '%' . $nomProduit . '%')
            ->setParameter('plateformes', 'Pc');

            $produits = $query->getResult();
            return $this->render('produits/pc/search.html.twig', [
                'searchedproduit' => $produits,
                'recherche' => $nomProduit
            ]);
        }

    }  

    // Tri les produits de la plateforme Pc dans le panel standard par ordre décroissant du prix
    #[Route('/search-pc-by-price-desc', name: 'pc-search-by-price-desc')]
    public function searchbypricedesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePc = $plateformeRepository->findOneBy(['libelle' => 'Pc']);
        if ($plateformePc === null) {
            // Gérer le cas où la plateforme "Pc" n'existe pas
            throw $this->createNotFoundException('La plateforme "Pc" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePc)
            ->orderBy('p.prix', 'DESC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/pc/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
 

    }   

    // Tri de la plateforme Pc dans le panel standard par ordre croissant du prix
    #[Route('/search-pc-by-price-asc', name: 'pc-search-by-price-asc')]
    public function searchbypriceasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePc = $plateformeRepository->findOneBy(['libelle' => 'Pc']);
        if ($plateformePc === null) {
            // Gérer le cas où la plateforme "Playstation" n'existe pas
            throw $this->createNotFoundException('La plateforme "Pc" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePc)
            ->orderBy('p.prix', 'ASC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/pc/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
    
    }   
    
}

