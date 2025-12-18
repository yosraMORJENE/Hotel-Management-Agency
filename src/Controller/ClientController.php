<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Hotel;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client/', name: 'client_list')]
    public function index(EntityManagerInterface $em): Response
    {
        $clients = $em->getRepository(Client::class)->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients
        ]);
    }

    #[Route('/client/add', name: 'client_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_list');
        }

        
        return $this->render('client/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/show/{id}', name: 'client_show')]
    public function show(EntityManagerInterface $em, $id): Response
    {
        $client = $em->getRepository(Client::class)->find($id);

      

        return $this->render('client/show.html.twig', [
            'client' => $client
        ]);
    }

    #[Route('/client/edit/{id}', name: 'client_edit')]
    public function edit(Request $request, EntityManagerInterface $em, $id): Response
    {
        $client = $em->getRepository(Client::class)->find($id);

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('client_list');
        }

       
        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client
        ]);
    }

    #[Route('/client/delete/{id}', name: 'client_delete')]
    public function delete(EntityManagerInterface $em, $id): Response
    {
        $client = $em->getRepository(Client::class)->find($id);

       
        $em->remove($client);
        $em->flush();

        return $this->redirectToRoute('client_list');
    }
}
