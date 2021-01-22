<?php

namespace App\Controller;

use App\Entity\HttpCredential;
use App\Form\HttpCredentialType;
use App\Repository\HttpCredentialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/http/credential")
 * @IsGranted("ROLE_SUPER_ADMIN", message="No tiene permisos para realizar esta acción.")
 */
class HttpCredentialController extends AbstractController {

    /**
     * @Route("/", name="http_credential_index", methods={"GET"})
     */
    public function index(HttpCredentialRepository $httpCredentialRepository): Response {
        return $this->render('http_credential/index.html.twig', [
                    'http_credentials' => $httpCredentialRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="http_credential_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $httpCredential = new HttpCredential();
        $form = $this->createForm(HttpCredentialType::class, $httpCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($httpCredential);
            $entityManager->flush();

            return $this->redirectToRoute('http_credential_index');
        }

        return $this->render('http_credential/new.html.twig', [
                    'http_credential' => $httpCredential,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="http_credential_show", methods={"GET"})
     */
    public function show(HttpCredential $httpCredential): Response {
        return $this->render('http_credential/show.html.twig', [
                    'http_credential' => $httpCredential,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="http_credential_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HttpCredential $httpCredential): Response {
        $form = $this->createForm(HttpCredentialType::class, $httpCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('http_credential_index');
        }

        return $this->render('http_credential/edit.html.twig', [
                    'http_credential' => $httpCredential,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="http_credential_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HttpCredential $httpCredential): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($httpCredential);
        $entityManager->flush();

        return $this->redirectToRoute('http_credential_index');
    }

}
