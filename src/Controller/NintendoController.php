<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;

class NintendoController extends AbstractController
{
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                             //
    // FONCTIONS CONCERNANTS LES RECHERCHES DES PRODUITS DE LA PLATEFORME "Nintendo" POUR LES VISITEURS DU SITE //
    //                                                                                                             //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    // Recherche par mot les produits de la plateforme PlayStation dans le panel standard de consultation des produits
    #[Route('/search-nintendo', name: 'search-nintendo')]
    public function search(EntityManagerInterface $entityManagerInterface, Request $request): Response
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

        $nomProduit = $request->query->get('search-input');   
        if( $nomProduit == ""){
            return $this->redirectToRoute('produit-nintendo');
        }else{
            $query = $entityManagerInterface->createQuery(
                'SELECT p FROM App\Entity\Produit p JOIN p.plateformes c WHERE p.designation LIKE :nomProduit AND c.libelle = :plateformes'
            )
            ->setParameter('nomProduit', '%' . $nomProduit . '%')
            ->setParameter('plateformes', 'Nintendo');

            $produits = $query->getResult();
            return $this->render('produits/nintendo/search.html.twig', [
                'searchedproduit' => $produits,
                'recherche' => $nomProduit
            ]);
        }

    }  

    // Tri les produits de la plateforme Nintendo dans le panel standard par ordre décroissant du prix
    #[Route('/search-nintendo-by-price-desc', name: 'nintendo-search-by-price-desc')]
    public function searchbypricedesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
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
            ->orderBy('p.prix', 'DESC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/nintendo/search-by-price-desc.html.twig', [
            'produits' => $produits,
        ]);
        

    }   

    // Tri les produits de la plateforme Nintendo dans le panel standard par ordre croissant du prix
    #[Route('/search-nintendo-by-price-asc', name: 'nintendo-search-by-price-asc')]
    public function searchbypriceasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
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
            ->orderBy('p.prix', 'ASC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/nintendo/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
        

    }   
}
