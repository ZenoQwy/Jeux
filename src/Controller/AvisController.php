<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AjoutAvisType;
use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AvisController extends AbstractController
{
    #[Route('/private-avis', name: 'avis')]
    public function avis(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AjoutAvisType::class, $avis);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $avis->setUser($this->getUser());
                $avis->setDateEnvoi(new \Datetime());
                $entityManagerInterface->persist($avis);
                $entityManagerInterface->flush();

                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('avis/index.html.twig', [
            'formavis' => $form->createView()
        ]);
    }

}
