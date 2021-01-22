<?php

namespace App\Controller;

use App\Entity\SnmpCredential;
use App\Form\SnmpCredentialType;
use App\Repository\SnmpCredentialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/snmp/credential")
 */
class SnmpCredentialController extends AbstractController
{
    /**
     * @Route("/", name="snmp_credential_index", methods={"GET"})
     */
    public function index(SnmpCredentialRepository $snmpCredentialRepository): Response
    {
        return $this->render('snmp_credential/index.html.twig', [
            'snmp_credentials' => $snmpCredentialRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="snmp_credential_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $snmpCredential = new SnmpCredential();
        $form = $this->createForm(SnmpCredentialType::class, $snmpCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($snmpCredential);
            $entityManager->flush();

            return $this->redirectToRoute('snmp_credential_index');
        }

        return $this->render('snmp_credential/new.html.twig', [
            'snmp_credential' => $snmpCredential,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="snmp_credential_show", methods={"GET"})
     */
    public function show(SnmpCredential $snmpCredential): Response
    {
        return $this->render('snmp_credential/show.html.twig', [
            'snmp_credential' => $snmpCredential,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="snmp_credential_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SnmpCredential $snmpCredential): Response
    {
        $form = $this->createForm(SnmpCredentialType::class, $snmpCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('snmp_credential_index');
        }

        return $this->render('snmp_credential/edit.html.twig', [
            'snmp_credential' => $snmpCredential,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="snmp_credential_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SnmpCredential $snmpCredential): Response
    {
        if ($this->isCsrfTokenValid('delete'.$snmpCredential->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($snmpCredential);
            $entityManager->flush();
        }

        return $this->redirectToRoute('snmp_credential_index');
    }
}
