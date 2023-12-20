<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;

class SearchController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////
    //                                                                  //
    // FONCTIONS CONCERNANTS LES RECHERCHES DANS LE PANEL ADMINISTATEUR //
    //                                                                  //
    //////////////////////////////////////////////////////////////////////

    // Recherche par mot des produits dans le panel admin
    #[Route('/admin-search', name: 'admin-search')]
    public function adminsearch(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $nomProduit = $request->query->get('search-input');   
        if( $nomProduit == ""){
            return $this->redirectToRoute('gestiondesproduits');
        }else{
            $repoProduits = $entityManagerInterface->getRepository(Produit::class);
            $query = $entityManagerInterface->createQuery(
                'SELECT p FROM App\Entity\Produit p WHERE p.designation LIKE :nomProduit'
            )->setParameter('nomProduit', '%'.$nomProduit.'%'); 
            $produits = $query->getResult();

            return $this->render('admin-search/search.html.twig', [
                'searchedproduit' => $produits,
                'recherche' => $nomProduit
            ]);
        }
    }


    // Tri des produits dans le panel admin par ordre décroissant du nombre de like 
    #[Route('/admin-search-by-likes-desc', name: 'admin-search-by-likes-desc')]
    public function adminsearchbylikesdesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $query = $entityManagerInterface->createQueryBuilder()
        ->select('p')
        ->from(Produit::class, 'p')
        ->orderBy('p.nblikes', 'DESC')
        ->getQuery();
    
        $produits = $query->getResult();

        return $this->render('admin-search/search-by-likes-desc.html.twig', [
            'produits' => $produits,
        ]);

    }

    // Tri des produits dans le panel admin par ordre croissant du nombre de like 
    #[Route('/admin-search-by-likes-asc', name: 'admin-search-by-likes-asc')]
    public function adminsearchbylikesasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $query = $entityManagerInterface->createQueryBuilder()
        ->select('p')
        ->from(Produit::class, 'p')
        ->orderBy('p.nblikes', 'ASC')
        ->getQuery();
    
        $produits = $query->getResult();

        return $this->render('admin-search/search-by-likes-asc.html.twig', [
            'produits' => $produits,
        ]);

    }

    // Tri des produits dans le panel admin par ordre décroissant du prix 
    #[Route('/admin-search-by-price-desc', name: 'admin-search-by-price-desc')]
    public function adminsearchbypricedesc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $query = $entityManagerInterface->createQueryBuilder()
        ->select('p')
        ->from(Produit::class, 'p')
        ->orderBy('p.prix', 'DESC')
        ->getQuery();
    
        $produits = $query->getResult();

        return $this->render('admin-search/search-by-price-desc.html.twig', [
            'produits' => $produits,
        ]);

    }

    // Tri des produits dans le panel admin par ordre croissant du prix 
    #[Route('/admin-search-by-price-asc', name: 'admin-search-by-price-asc')]
    public function adminsearchbypricesasc(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $query = $entityManagerInterface->createQueryBuilder()
        ->select('p')
        ->from(Produit::class, 'p')
        ->orderBy('p.prix', 'ASC')
        ->getQuery();
    
        $produits = $query->getResult();

        return $this->render('admin-search/search-by-price-asc.html.twig', [
            'produits' => $produits,
        ]);
    }
}
