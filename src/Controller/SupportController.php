<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SupportType;
use App\Entity\Support;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SupportController extends AbstractController
{
    #[Route('/private-support', name: 'support')]
    public function support(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $support = new Support();
        $form = $this->createForm(SupportType::class, $support);
        $repoUsers= $entityManagerInterface->getRepository(User::class);
        $user= $repoUsers->find(21);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $support->setEmail($this->getUser()->getEmail());
                $support->setDateEnvoie(new \Datetime());
                $support->setEnvoyeur($this->getUser());
                $support->setDestinataire($user);
                $entityManagerInterface->persist($support);
                $entityManagerInterface->flush();

                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('support/index.html.twig', [
            'formsupport' => $form->createView()
        ]);
    }

    #[Route('/admin-support-messagerie', name: 'adminmessagerie')]
    public function adminmessagerie(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if ($this->getUser()->getReceivedSupports() != null){   
            $repoSupport = $entityManagerInterface->getRepository(Support::class);
            $lesmessages= $repoSupport->findAll();
        }
        
        return $this->render('support/admin-messagerie.html.twig', [
            'lesmessages' => $lesmessages
        ]);
    }

    #[Route('/admin-support-message/{id}', name: 'adminmessage')]
    public function adminmessage(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $id = $request->get('id');
        $repoSupport = $entityManagerInterface->getRepository(Support::class);
        $lemessage= $repoSupport->find($id);

        $repoUsers = $entityManagerInterface->getRepository(User::class);
        $user = $repoUsers->findOneBy(['email' => $lemessage->getEmail()]);

        
        return $this->render('support/admin-message.html.twig', [
            'lemessage' => $lemessage,
            'user' => $user
        ]);
    }

    #[Route('/admin-supprimer-support-message/{id}', name: 'adminsupprimersupport')]
    public function adminsupprimersupport(Request $request, EntityManagerInterface $entityManagerInterface): Response
    { 
            $id = $request->get('id'); 
            $repoSupport = $entityManagerInterface->getRepository(Support::class);
            $lemessages= $repoSupport->find($id);
            $entityManagerInterface->remove($lemessages);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('adminmessagerie');  
    }



    #[Route('/private-repondre-support-{id}', name: 'repondresupport')]
    public function repondresupport(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $id = $request->get('id');
        $repoSupport = $entityManagerInterface->getRepository(Support::class);
        $lesupport= $repoSupport->find($id);
        $form = $this->createForm(SupportType::class,$produits);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $support->setEmail($this->getUser()->getEmail());
                $support->setDateEnvoie(new \Datetime());
                $support->setEnvoyeur($this->getUser());
                $support->setDestinataire($user);
                $entityManagerInterface->persist($support);
                $entityManagerInterface->flush();

                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('support/index.html.twig', [
            'formsupport' => $form->createView()
        ]);
    }

    #[Route('/private-messagerie', name: 'messagerie')]
    public function messagerie(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $repoSupport= $entityManagerInterface->getRepository(Support::class);
        if ($this->getUser()->getSupports() != null){   
            $support->getDestinataire($this->getUser());
            dump($support);
            return $this->redirectToRoute('index');
        }
        
        return $this->render('base.html.twig', [
            'tesmessages' => $support
        ]);
    }
}
