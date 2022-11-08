<?php

namespace App\Controller;

use App\Entity\InvoiceLines;
use App\Form\InvoiceLinesType;
use App\Repository\InvoiceLinesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice-lines')]
class InvoiceLinesController extends AbstractController
{
    #[Route('/', name: 'app_invoice_lines_index', methods: ['GET'])]
    public function index(InvoiceLinesRepository $invoiceLinesRepository): Response
    {
        return $this->render('invoice_lines/index.html.twig', [
            'invoice_lines' => $invoiceLinesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invoice_lines_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InvoiceLinesRepository $invoiceLinesRepository): Response
    {
        $invoiceLine = new InvoiceLines();
        $form = $this->createForm(InvoiceLinesType::class, $invoiceLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceLinesRepository->save($invoiceLine, true);

            return $this->redirectToRoute('app_invoice_lines_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invoice_lines/new.html.twig', [
            'invoice_line' => $invoiceLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_lines_show', methods: ['GET'])]
    public function show(InvoiceLines $invoiceLine): Response
    {
        return $this->render('invoice_lines/show.html.twig', [
            'invoice_line' => $invoiceLine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_lines_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InvoiceLines $invoiceLine, InvoiceLinesRepository $invoiceLinesRepository): Response
    {
        $form = $this->createForm(InvoiceLinesType::class, $invoiceLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceLinesRepository->save($invoiceLine, true);

            return $this->redirectToRoute('app_invoice_lines_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invoice_lines/edit.html.twig', [
            'invoice_line' => $invoiceLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_lines_delete', methods: ['POST'])]
    public function delete(Request $request, InvoiceLines $invoiceLine, InvoiceLinesRepository $invoiceLinesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoiceLine->getId(), $request->request->get('_token'))) {
            $invoiceLinesRepository->remove($invoiceLine, true);
        }

        return $this->redirectToRoute('app_invoice_lines_index', [], Response::HTTP_SEE_OTHER);
    }
}
