<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;

class XboxController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                      //
    // FONCTIONS CONCERNANTS LES RECHERCHES DES PRODUITS DE LA PLATEFORME "Xbox" POUR LES VISITEURS DU SITE //
    //                                                                                                      //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////


    // Recherche par mot les produits de la plateforme Xbox dans le panel standard de consultation des produits
    #[Route('/search-xbox', name: 'search-xbox')]
    public function search(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformeXbox = $plateformeRepository->findOneBy(['libelle' => 'Xbox']);
        if ($plateformeXbox === null) {
            // Gérer le cas où la plateforme "Xbox" n'existe pas
            throw $this->createNotFoundException('La plateforme "Xbox" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeXbox)
            ->getQuery();
        
        $produits = $query->getResult();

        $nomProduit = $request->query->get('search-input');   
        if( $nomProduit == ""){
            return $this->redirectToRoute('produit-xbox');
        }else{
            $query = $entityManagerInterface->createQuery(
                'SELECT p FROM App\Entity\Produit p JOIN p.plateformes c WHERE p.designation LIKE :nomProduit AND c.libelle = :plateformes'
            )
            ->setParameter('nomProduit', '%' . $nomProduit . '%')
            ->setParameter('plateformes', 'Xbox');

            $produits = $query->getResult();
            return $this->render('produits/xbox/search.html.twig', [
                'searchedproduit' => $produits,
                'recherche' => $nomProduit
            ]);
        }

    }  

    // Tri les produits de la plateforme Xbox dans le panel standard par ordre décroissant du prix
    #[Route('/search-xbox-by-price-desc', name: 'xbox-search-by-price-desc')]
    public function searchbypricedesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformeXbox = $plateformeRepository->findOneBy(['libelle' => 'Xbox']);
        if ($plateformeXbox === null) {
            // Gérer le cas où la plateforme "Xbox" n'existe pas
            throw $this->createNotFoundException('La plateforme "Xbox" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeXbox)
            ->orderBy('p.prix', 'DESC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/xbox/search-by-price-desc.html.twig', [
            'produits' => $produits,
        ]);
 

    }   

    // Tri de la plateforme Xbox dans le panel standard par ordre croissant du prix
    #[Route('/search-xbox-by-price-asc', name: 'xbox-search-by-price-asc')]
    public function searchbypriceasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformeXbox = $plateformeRepository->findOneBy(['libelle' => 'Xbox']);
        if ($plateformeXbox === null) {
            // Gérer le cas où la Xbox "Xbox" n'existe pas
            throw $this->createNotFoundException('La plateforme "Xbox" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformeXbox)
            ->orderBy('p.prix', 'ASC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/xbox/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
    
    }   
    
}