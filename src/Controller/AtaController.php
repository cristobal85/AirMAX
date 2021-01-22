<?php

namespace App\Controller;

use App\Entity\Ata;
use App\Form\AtaType;
use App\Repository\AtaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repository\DhcpConfigRepository;
use App\Ssh\Adapter\SshAdapter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ssh\Command\Dhcp\SshDhcpLogAta;
use App\Ssh\Command\Dhcp\SshDhcpGetIp;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Client;
use App\Ssh\Command\Dhcp\SshDhcpRegisterAta;
use App\Ssh\Command\Dhcp\SshDhcpScriptRestartCommand;
use App\Ssh\Exception\SshExceptionInterface;
use App\Ssh\Command\Dhcp\SshDhcpUnregisterAta;

/**
 * @Route("/ata")
 */
class AtaController extends AbstractController {

    /**
     * @Route("/", name="ata_index", methods={"GET"})
     */
    public function index(AtaRepository $ataRepository): Response {
        return $this->render('ata/index.html.twig', [
                    'atas' => $ataRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="ata_new", methods={"GET","POST"})
     */
    public function new(
            Request $request,
            Client $client,
            DhcpConfigRepository $dhcpConfRep): Response {
        $atum = new Ata();
        $atum->setClient($client);
        $form = $this->createForm(AtaType::class, $atum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($atum->getAddress())) {
                $atum->setAddress($atum->getClient()->getAdress());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atum);

            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            try {
                $cmd = new SshDhcpRegisterAta($sshAdapter, $dhcpConfig, $atum);
                $cmd->execute();
                $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $atum);
                $dhcpScriptCmd->execute();
            } catch (SshExceptionInterface $ex) {
                throw new BadRequestHttpException($ex->getMessage());
            }

            $entityManager->flush();

            return $this->redirectToRoute('client_show_atas', [
                        'id' => $client->getId()
            ]);
        }

        return $this->render('ata/new.html.twig', [
                    'atum' => $atum,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ata_show", methods={"GET"})
     */
    public function show(Ata $atum): Response {
        return $this->render('ata/show.html.twig', [
                    'atum' => $atum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ata_edit", methods={"GET","POST"})
     */
    public function edit(
            Request $request,
            Ata $atum,
            DhcpConfigRepository $dhcpConfRep): Response {
        $form = $this->createForm(AtaType::class, $atum, ['action' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            $cmd = new SshDhcpUnregisterAta($sshAdapter, $dhcpConfig, $atum);
            $cmd->execute();
            if ($atum->getActive()) {
                $cmd = new SshDhcpRegisterAta($sshAdapter, $dhcpConfig, $atum);
                $cmd->execute();
            }
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $atum);
            $dhcpScriptCmd->execute();

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show_atas', [
                        'id' => $atum->getClient()->getId()
            ]);
        }

        return $this->render('ata/edit.html.twig', [
                    'atum' => $atum,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ata_delete", methods={"DELETE"})
     */
    public function delete(
            Request $request,
            Ata $atum,
            DhcpConfigRepository $dhcpConfRep): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }

        try {
            $cmd = new SshDhcpUnregisterAta($sshAdapter, $dhcpConfig, $atum);
            $cmd->execute();
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $atum);
            $dhcpScriptCmd->execute();
        } catch (SshExceptionInterface $ex) {
            throw new BadRequestHttpException($ex->getMessage());
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($atum);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'ATA ' . $atum . ' eliminada correctamente'
        ]);
    }

    /**
     * @Route("/{id}/show-log", name="ata_show_log", methods={"GET"})
     */
    public function showLog(
            Request $request,
            Ata $ata,
            DhcpConfigRepository $dhcpConfRep): Response {

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }
        $cmd = new SshDhcpLogAta($sshAdapter, $dhcpConfig, $ata);
        $log = $cmd->execute();

        return $this->render('log/show_log.html.twig', [
                    'log' => $log
        ]);
    }

    /**
     * @Route("/{id}/remote-access", name="ata_remote_access", methods={"GET"})
     */
    public function remoteAccess(
            Request $request,
            Ata $ata,
            DhcpConfigRepository $dhcpConfRep): Response {

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }
        try {
            $cmd = new SshDhcpGetIp($sshAdapter, $dhcpConfig, $ata);
            $ip = $cmd->execute();
        } catch (SshExceptionInterface $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
                    ], Response::HTTP_BAD_REQUEST);
        }


        $protocol = $_ENV['ATA_HTTP_PROTOCOL'];
        $port = $_ENV['ATA_HTTP_PORT'];
        $httpCredential = $ata->getHttpCredential();
        if (!empty($httpCredential)) {
            $protocol = $httpCredential->getProtocol();
            $port = $httpCredential->getPort();
        }
        $url = $protocol . '://' . $ip . ':' . $port;


        return new JsonResponse([
            'url' => $url
        ]);
    }

}
