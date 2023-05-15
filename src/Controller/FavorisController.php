<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;

class FavorisController extends AbstractController
{
    #[Route('/private-favoris', name: 'favoris')]
    public function listfavoris(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        return $this->render('favoris/index.html.twig', [
        ]);
    }

    #[Route('/private-ajout|retrait-favoris', name: 'ajout|retrait-favoris')]
    public function ajoutouretraitfavoris(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $id = $request->get('id');
		$produit = $entityManagerInterface->getRepository(Produit::class)->find($id);	
		$action = $request->get('action');
	
		if ($action == 'supprimer'){
			$this->getUSer()->removeFavori($produit); // get le user connecté et on remove des favoris 
            $produit->setNbLikes($produit->getNbLikes()-1);
            $entityManagerInterface->flush();
        }
           	
        if ($action == 'ajouter'){
			$this->getUSer()->addFavori($produit);
            $produit->setNbLikes($produit->getNbLikes()+1); // ajout de 1 likes dans la table produit 
			$entityManagerInterface->persist($produit);
           	$entityManagerInterface->flush();
        }
        $refererUrl = $request->headers->get('referer');
        return $this->redirect($refererUrl); 
    }
}
