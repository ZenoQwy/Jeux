<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Plateforme;


class PlayStationController extends AbstractController
{
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                             //
    // FONCTIONS CONCERNANTS LES RECHERCHES DES PRODUITS DE LA PLATEFORME "PlayStation" POUR LES VISITEURS DU SITE //
    //                                                                                                             //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    // Recherche par mot les produits de la plateforme PlayStation dans le panel standard de consultation des produits
    #[Route('/search-playstation', name: 'search-playstation')]
    public function search(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePlaystation = $plateformeRepository->findOneBy(['libelle' => 'PlayStation']);
        if ($plateformePlaystation === null) {
            // Gérer le cas où la plateforme "PlayStation" n'existe pas
            throw $this->createNotFoundException('La plateforme "Pc" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePlaystation)
            ->getQuery();
        
        $produits = $query->getResult();

        $nomProduit = $request->query->get('search-input');   
        if( $nomProduit == ""){
            return $this->redirectToRoute('produit-playstation');
        }else{
            $query = $entityManagerInterface->createQuery(
                'SELECT p FROM App\Entity\Produit p JOIN p.plateformes c WHERE p.designation LIKE :nomProduit AND c.libelle = :plateformes'
            )
            ->setParameter('nomProduit', '%' . $nomProduit . '%')
            ->setParameter('plateformes', 'Playstation');

            $produits = $query->getResult();
            return $this->render('produits/playstation/search.html.twig', [
                'searchedproduit' => $produits,
                'recherche' => $nomProduit
            ]);
        }

    }  

    // Tri les produits de la plateforme PlayStation dans le panel standard par ordre décroissant du prix
    #[Route('/search-playstation-by-price-desc', name: 'playstation-search-by-price-desc')]
    public function searchbypricedesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePlaystation = $plateformeRepository->findOneBy(['libelle' => 'Playstation']);
        if ($plateformePlaystation === null) {
            // Gérer le cas où la plateforme "Playstation" n'existe pas
            throw $this->createNotFoundException('La plateforme "Playstation" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePlaystation)
            ->orderBy('p.prix', 'DESC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/playstation/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
 

    }   

    // Tri de la plateforme Playstation dans le panel standard par ordre croissant du prix
    #[Route('/search-playstation-by-price-asc', name: 'playstation-search-by-price-asc')]
    public function searchbypriceasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $plateformeRepository = $entityManagerInterface->getRepository(Plateforme::class);
        $plateformePlaystation = $plateformeRepository->findOneBy(['libelle' => 'Playstation']);
        if ($plateformePlaystation === null) {
            // Gérer le cas où la plateforme "Playstation" n'existe pas
            throw $this->createNotFoundException('La plateforme "Playstation" n\'existe pas.');
        }
        
        $query = $repoProduits->createQueryBuilder('p')
            ->leftJoin('p.plateformes', 'pl')
            ->where('pl = :plateforme')
            ->setParameter('plateforme', $plateformePlaystation)
            ->orderBy('p.prix', 'ASC')
            ->getQuery();
        
        $produits = $query->getResult();
        
        return $this->render('produits/playstation/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
    
    }   
    
}
