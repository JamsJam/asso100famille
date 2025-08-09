<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Form\AdherentForm;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adherents')]
final class AdherentController extends AbstractController
{
    #[Route(name: 'app_admin_adherent_index', methods: ['GET'])]
    public function index(
        AdherentRepository $adherentRepository,
        Request $request,
    ): Response
    {

        $page = intval($request->query->get('page') ?? 1);
        $offset = $page - 1;

        $maxsizelist = 10;
        $adherents = $adherentRepository->findby(['isVerified' => true],['id'=> 'asc']);
        $totalAdherents =  count($adherents);
        $adherentsList = array_slice($adherents, $offset*$maxsizelist ,$maxsizelist);
        
        $countPage = intval(ceil($totalAdherents/$maxsizelist));
        dump($page,$offset);

        

        return $this->render('admin/adherent/index.html.twig', [
            'adherents' => $adherentsList,
            'itemCount' => $totalAdherents,
            'sizeList' => $maxsizelist,
            'page' => $page,
            'pageCount' => $countPage


        ]);
    }

    #[Route('/new', name: 'app_admin_adherent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adherent = new Adherent();
        $form = $this->createForm(AdherentForm::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/adherent/new.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_adherent_show', methods: ['GET'])]
    public function show(Adherent $adherent): Response
    {
        return $this->render('admin/adherent/show.html.twig', [
            'adherent' => $adherent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_adherent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherent $adherent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdherentForm::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/adherent/edit.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_adherent_delete', methods: ['POST'])]
    public function delete(Request $request, Adherent $adherent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adherent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_adherent_index', [], Response::HTTP_SEE_OTHER);
    }
}
