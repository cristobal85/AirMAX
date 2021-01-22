<?php

namespace App\Controller;

use App\Entity\SshCredential;
use App\Form\SshCredentialType;
use App\Repository\SshCredentialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/ssh/credential")
 */
class SshCredentialController extends AbstractController {

    /**
     * @Route("/", name="ssh_credential_index", methods={"GET"})
     */
    public function index(SshCredentialRepository $sshCredentialRepository): Response {
        return $this->render('ssh_credential/index.html.twig', [
                    'ssh_credentials' => $sshCredentialRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ssh_credential_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $sshCredential = new SshCredential();
        $form = $this->createForm(SshCredentialType::class, $sshCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sshCredential);
            $entityManager->flush();

            return $this->redirectToRoute('ssh_credential_index');
        }

        return $this->render('ssh_credential/new.html.twig', [
                    'ssh_credential' => $sshCredential,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ssh_credential_show", methods={"GET"})
     */
    public function show(SshCredential $sshCredential): Response {
        return $this->render('ssh_credential/show.html.twig', [
                    'ssh_credential' => $sshCredential,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ssh_credential_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SshCredential $sshCredential): Response {
        $form = $this->createForm(SshCredentialType::class, $sshCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ssh_credential_index');
        }

        return $this->render('ssh_credential/edit.html.twig', [
                    'ssh_credential' => $sshCredential,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ssh_credential_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SshCredential $sshCredential): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sshCredential);
        $entityManager->flush();

        return $this->redirectToRoute('ssh_credential_index');
    }

}
