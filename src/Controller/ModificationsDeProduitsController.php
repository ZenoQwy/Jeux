<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\AjoutProduitType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;



class ModificationsDeProduitsController extends AbstractController
{
    #[Route('/admin-gestion-des-produits', name: 'gestiondesproduits')]
    public function listmodifproduit(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produits= $repoProduits->findAll();
        return $this->render('modifications_de_produits/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/admin-modif-produit/{designation}', name: 'modifierleproduit')]
    public function modifproduit(EntityManagerInterface $entityManagerInterface, Request $request, SluggerInterface $slugger): Response
    {
        $id = $request->get('id');
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produits= $repoProduits->find($id);
        $plateformesExistantes = $produits->getPlateformes();

        $form = $this->createForm(ProduitType::class,$produits);

        if($request-> isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $nouvellesPlateformes = $produits->getPlateformes();
                foreach ($plateformesExistantes as $plateformeExistante) {
                    if (!$nouvellesPlateformes->contains($plateformeExistante)) {
                        $produits->removePlateforme($plateformeExistante);
                    }
                }

                $fichier = $form->get('image')->getData();

                if ($fichier!=null && $fichier!='null' && $fichier!='') {
                    $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichier = $slugger->slug($nomFichier);
                    //$nomFichier = $nomFichier.'-'.uniqid().'.'.$fichier->guessExtension();
                    $nomFichier = $nomFichier.'.'.$fichier->guessExtension();

                    $filesystem = new Filesystem();
                    $cheminFichier = $this->getParameter('file_directory').'/'.$nomFichier;
                
                    // Supprimer le fichier existant s'il existe
                    if ($filesystem->exists($cheminFichier)) {
                        $filesystem->remove($cheminFichier);
                    }
                    
                    try {
                        $fichier->move($this->getParameter('file_directory'), $nomFichier);
                        $produits->setImage($nomFichier);
                        $entityManagerInterface->persist($produits);
                        $entityManagerInterface->flush();
                        $this->addFlash('notice','Produit modifié !');
                        } catch (FileException $e) {
                            $this->addFlash('notice', 'Erreur lors de la modification du produit');
                        }
                } else {
                    $entityManagerInterface->persist($produits);
                    $entityManagerInterface->flush();
                    $this->addFlash('notice','Produit modifié !');
                }
                return $this->redirectToRoute('gestiondesproduits');
            }
        }
        return $this->render('modifications_de_produits/modifleproduit.html.twig', [
            'produit' => $produits,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin-supprimer-produit/{id}', name: 'supproduit')]
    public function supproduit(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {        
        $id = $request->get('id');
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produits= $repoProduits->find($id);
        $entityManagerInterface->remove($produits);
        $entityManagerInterface->flush();

        $this->addFlash('notice','Produit supprimé !');
        return $this->redirectToRoute('gestiondesproduits');  
            
    }

    #[Route('/admin-ajouter-un-produit', name: 'ajoutproduit')]
    public function ajoutprod(EntityManagerInterface $entityManagerInterface, Request $request, SluggerInterface $slugger): Response

    {   
    $produit = new Produit();     
    $form = $this->createForm(AjoutProduitType::class, $produit);

    if ($request->isMethod('POST')) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $fichier = $form->get('image')->getData();

            if ($fichier) {
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier = $slugger->slug($nomFichier);
                //$nomFichier = $nomFichier.'-'.uniqid().'.'.$fichier->guessExtension();
                $nomFichier = $nomFichier.'.'.$fichier->guessExtension();

                $filesystem = new Filesystem();
                $cheminFichier = $this->getParameter('file_directory').'/'.$nomFichier;
            
                // Supprimer le fichier existant s'il existe
                if ($filesystem->exists($cheminFichier)) {
                    $filesystem->remove($cheminFichier);
                }
                
                try {
                    $fichier->move($this->getParameter('file_directory'), $nomFichier);
                    $produit->setImage($nomFichier);
                   
                    $plateformes = $form->get('plateformes')->getData();
                    // Associez les plateformes au produit
                    foreach ($plateformes as $plateforme) {
                        $produit->addPlateforme($plateforme);
                    }
                    $entityManagerInterface->persist($produit);
                    $entityManagerInterface->flush();
                    $this->addFlash('notice', 'Produit ajouté avec succès');
                } catch (FileException $e) {
                    $this->addFlash('notice', 'Erreur lors de l\'ajout du produit');
                }
            }

            return $this->redirectToRoute('gestiondesproduits');
        }
    } 

    return $this->render('modifications_de_produits/ajout-produit.html.twig', [
        'ajout' => $form->createView()
    ]);
    }

    #[Route('/produit/admin-gestion-produit-afficher/{id}', name: 'gestion-affichage-produit')]
    public function gestionshowproduit(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $id = $request->get('id');
        $repoProduits = $entityManagerInterface->getRepository(Produit::class);
        $produit = $repoProduits->find($id);
        return $this->render('modifications_de_produits/afficherleproduit.html.twig', [
            'adminproduit' => $produit
        ]);
  
    }
}

