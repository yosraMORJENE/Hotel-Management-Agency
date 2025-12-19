<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Client;
use App\Form\HotelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotel/', name: 'hotel_list')]
    public function index(EntityManagerInterface $em): Response
    {
        $hotels = $em->getRepository(Hotel::class)->findAll();

        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotels
        ]);
    }

    #[Route('/hotel/add', name: 'hotel_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile instanceof UploadedFile) {
                $uploadDir = (string) $this->getParameter('hotel_upload_dir');
                $publicPrefix = (string) $this->getParameter('hotel_upload_public_path');

                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }

                $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $hotel->getNomHotel());
                $safeBase = trim((string) $safeBase, '-') ?: 'hotel';
                $ext = $imageFile->guessExtension() ?: 'bin';
                $fileName = sprintf('%s-%s.%s', strtolower($safeBase), bin2hex(random_bytes(6)), $ext);

                $imageFile->move($uploadDir, $fileName);

                // Store a web-accessible URL (works in <img src="...">)
                $hotel->setImage(rtrim($publicPrefix, '/') . '/' . $fileName);
            }

            $em->persist($hotel);
            $em->flush();

            $this->addFlash('success', 'Hotel created successfully.');

            return $this->redirectToRoute('hotel_list');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fix the form errors and try again.');
        }

        
        return $this->render('hotel/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/hotel/show/{id}', name: 'hotel_show')]
    public function show(EntityManagerInterface $em, $id): Response
    {
        $hotel = $em->getRepository(Hotel::class)->find($id);

       

        $clients = $em->getRepository(Client::class)->findBy([
            'hotel' => $hotel
        ]);

        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
            'clients' => $clients
        ]);
    }

    #[Route('/hotel/edit/{id}', name: 'hotel_edit')]
    public function edit(Request $request, EntityManagerInterface $em, $id): Response
    {
        $hotel = $em->getRepository(Hotel::class)->find($id);

       

        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $previousImage = $hotel->getImage();

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile instanceof UploadedFile) {
                $uploadDir = (string) $this->getParameter('hotel_upload_dir');
                $publicPrefix = (string) $this->getParameter('hotel_upload_public_path');

                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }

                $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $hotel->getNomHotel());
                $safeBase = trim((string) $safeBase, '-') ?: 'hotel';
                $ext = $imageFile->guessExtension() ?: 'bin';
                $fileName = sprintf('%s-%s.%s', strtolower($safeBase), bin2hex(random_bytes(6)), $ext);

                $imageFile->move($uploadDir, $fileName);
                $hotel->setImage(rtrim($publicPrefix, '/') . '/' . $fileName);

                if (is_string($previousImage) && str_starts_with($previousImage, rtrim($publicPrefix, '/') . '/')) {
                    $oldFile = $uploadDir . DIRECTORY_SEPARATOR . basename($previousImage);
                    if (is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            $em->flush();

            $this->addFlash('success', 'Hotel updated successfully.');
            return $this->redirectToRoute('hotel_list');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fix the form errors and try again.');
        }

        
        return $this->render('hotel/edit.html.twig', [
            'form' => $form->createView(),
            'hotel' => $hotel
        ]);
    }

    #[Route('/hotel/delete/{id}', name: 'hotel_delete')]
    public function delete(EntityManagerInterface $em, $id): Response
    {
        $hotel = $em->getRepository(Hotel::class)->find($id);

        
        $em->remove($hotel);
        $em->flush();

        return $this->redirectToRoute('hotel_list');
    }

}
