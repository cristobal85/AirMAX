<?php

namespace App\Controller;

use App\Entity\Antenna;
use App\Entity\Client;
use App\Form\AntennaType;
use App\Repository\DhcpConfigRepository;
use App\Ssh\Adapter\SshAdapter;
use App\Ssh\Command\Dhcp\SshDhcpGetIp;
use App\Ssh\Command\Dhcp\SshDhcpLogAntenna;
use App\Ssh\Command\Dhcp\SshDhcpRegisterAntenna;
use App\Ssh\Command\Dhcp\SshDhcpScriptRestartCommand;
use App\Ssh\Command\Dhcp\SshDhcpUnregisterAntenna;
use App\Ssh\Exception\SshExceptionInterface;
use App\Ssh\Exception\SshExecException;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/antenna")
 * @IsGranted("ROLE_ADMIN", message="No tiene permisos para realizar esta acción.")
 */
class AntennaController extends AbstractController {

    /**
     * @Route("/{id}/new", name="client_antenna_new", methods={"GET","POST"})
     */
    public function new(
            Request $request,
            Client $client,
            DhcpConfigRepository $dhcpConfRep): Response {
        $antenna = new Antenna();
        $antenna->setClient($client);
        $form = $this->createForm(AntennaType::class, $antenna);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($antenna);

            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            try {
                $cmd = new SshDhcpRegisterAntenna($sshAdapter, $dhcpConfig, $antenna);
                $cmd->execute();
                $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $antenna);
                $dhcpScriptCmd->execute();
            } catch (SshExceptionInterface $ex) {
                throw new BadRequestHttpException($ex->getMessage());
            }

            $entityManager->flush();
            return $this->redirectToRoute('client_show_antennas', [
                        'id' => $client->getId()
            ]);
        }

        return $this->render('antenna/new.html.twig', [
                    'antenna' => $antenna,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_antenna_show", methods={"GET"})
     */
    public function show(Antenna $antenna): Response {
        return $this->render('antenna/show.html.twig', [
                    'antenna' => $antenna,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_antenna_edit", methods={"GET","POST"})
     */
    public function edit(
            Request $request,
            Antenna $antenna,
            DhcpConfigRepository $dhcpConfRep): Response {
        $form = $this->createForm(AntennaType::class, $antenna, ['action' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            $cmd = new SshDhcpUnregisterAntenna($sshAdapter, $dhcpConfig, $antenna);
            $cmd->execute();
            if ($antenna->getActive()) {
                $cmd = new SshDhcpRegisterAntenna($sshAdapter, $dhcpConfig, $antenna);
                $cmd->execute();
            }
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $antenna);
            $dhcpScriptCmd->execute();

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('client_show_antennas', ['id' => $antenna->getClient()->getId()]);
        }

        return $this->render('antenna/edit.html.twig', [
                    'antenna' => $antenna,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_antenna_delete", methods={"DELETE"})
     */
    public function delete(
            Request $request,
            Antenna $antenna,
            EntityManagerInterface $em,
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
            $cmd = new SshDhcpUnregisterAntenna($sshAdapter, $dhcpConfig, $antenna);
            $cmd->execute();
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $antenna);
            $dhcpScriptCmd->execute();
        } catch (SshExceptionInterface $ex) {
            return new JsonResponse([
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
//            throw new BadRequestHttpException($ex->getMessage());
        }

        $em->remove($antenna);
        $em->flush();

        return new JsonResponse([
            'message' => 'Antena ' . $antenna . ' eliminada correctamente'
        ]);
    }

    /**
     * @Route("/{id}/show-log", name="client_antenna_show_log", methods={"GET"})
     */
    public function showLog(
            Request $request,
            Antenna $antenna,
            DhcpConfigRepository $dhcpConfRep): Response {

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }
        $cmd = new SshDhcpLogAntenna($sshAdapter, $dhcpConfig, $antenna);
        $log = $cmd->execute();

        return $this->render('log/show_log.html.twig', [
                    'log' => $log
        ]);
    }

    /**
     * @Route("/{id}/remote-access", name="client_antenna_remote_access", methods={"GET"})
     */
    public function remoteAccess(
            Request $request,
            Antenna $antenna,
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
            $cmd = new SshDhcpGetIp($sshAdapter, $dhcpConfig, $antenna);
            $ip = $cmd->execute();
        } catch (SshExecException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $protocol = $_ENV['ANTENNA_HTTP_PROTOCOL'];
        $port = $_ENV['ANTENNA_HTTP_PORT'];
        $httpCredential = $antenna->getHttpCredential();
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
