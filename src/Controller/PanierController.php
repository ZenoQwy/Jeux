<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Entity\Ajouter;
use App\Entity\Panier;
use App\Entity\User;

class PanierController extends AbstractController
{
    #[Route('/private-ajouter-un-produit-au-panier', name: 'ajoutpanier')]
    public function index(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $id = $request->get('id');
        $page = $request->get('page');
        if($this->getUser()->getPanier()==null){  // si le panier du l'utilisateur connecté est vide / non-existant
            $panier = new Panier();
            $this->getUser()->setPanier($panier);
            $entityManagerInterface->persist($this->getUser());
            $entityManagerInterface->flush();
        } 
        $ajouter = $entityManagerInterface->getRepository(Ajouter::class)->findOneBy(['produits' => $id, 'panier' => $this->getUser()->getPanier()]);
        
        if($ajouter != null){
            // si le produit est déjà dans le panier, on incrémente la quantité
            $ajouter->setQte($ajouter->getQte() + 1);
        } else {
            // sinon, on crée une nouvelle ligne dans la table ajouter
            $ajouter = new Ajouter();
            $ajouter->setQte('1');
            $ajouter->setProduits($entityManagerInterface->getRepository(Produit::class)->find($id));
            $ajouter->setPanier($this->getUser()->getPanier());
        }
        $entityManagerInterface->persist($ajouter);
        $entityManagerInterface->flush();
        $refererUrl = $request->headers->get('referer');
        return $this->redirect($refererUrl);
    }

    #[Route('/private-supprimer-un-produit-du-panier', name: 'supp1dupanier')]
    public function supp1produitpanier(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $id = $request->get('id');
        $page = $request->get('page');
        $ajouter = $entityManagerInterface->getRepository(Ajouter::class)->findOneBy(['produits' => $id, 'panier' => $this->getUser()->getPanier()]);
        
        // si le produit est déjà dans le panier
        if($ajouter != null){
            if($ajouter->getQte() > 1 ){
                $ajouter->setQte($ajouter->getQte() - 1);
                $entityManagerInterface->persist($ajouter);
                $entityManagerInterface->flush();
            }
        }
        $refererUrl = $request->headers->get('referer');
        return $this->redirect($refererUrl);
    }

    #[Route('/private-supprimer-panier/{id}', name: 'suppanier')]
    public function suppPanier(EntityManagerInterface $entityManagerInterface,Request $request): Response
    {
        $id=$request->get('id');
        $repoPanier = $entityManagerInterface->getRepository(Panier::class);
        $panier = $repoPanier->find($id);

        $repoAjouter = $entityManagerInterface->getRepository(Ajouter::class);
        $ajouter = $repoAjouter->find($id);
 
        $entityManagerInterface->remove($ajouter);
        $entityManagerInterface->flush();

        $this->addFlash('notice','Suppression Terminé');
        $refererUrl = $request->headers->get('referer');
        return $this->redirect($refererUrl);

    }


    #[Route('/private-panier', name: 'panier')]
    public function listePanier(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoPanier = $entityManagerInterface->getRepository(Panier::class);
        $panier = $repoPanier->findAll();
        return $this->render('panier/index.html.twig', [
           'panier' => $panier,
        ]);
    }
}
