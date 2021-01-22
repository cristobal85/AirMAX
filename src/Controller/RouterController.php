<?php

namespace App\Controller;

use App\Entity\Router;
use App\Form\RouterType;
use App\Repository\RouterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repository\DhcpConfigRepository;
use App\Ssh\Adapter\SshAdapter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ssh\Command\Dhcp\SshDhcpLogRouter;
use App\Ssh\Command\Dhcp\SshDhcpGetIp;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Client;
use App\Ssh\Command\Dhcp\SshDhcpRegisterRouter;
use App\Ssh\Command\Dhcp\SshDhcpScriptRestartCommand;
use App\Ssh\Exception\SshExceptionInterface;
use App\Ssh\Command\Dhcp\SshDhcpUnregisterRouter;

/**
 * @Route("/router")
 */
class RouterController extends AbstractController {

    /**
     * @Route("/", name="router_index", methods={"GET"})
     */
    public function index(RouterRepository $routerRepository): Response {
        return $this->render('router/index.html.twig', [
                    'routers' => $routerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="router_new", methods={"GET","POST"})
     */
    public function new(
            Request $request,
            Client $client,
            DhcpConfigRepository $dhcpConfRep): Response {
        $router = new Router();
        $router->setClient($client);
        $form = $this->createForm(RouterType::class, $router);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($router->getAddress())) {
                $router->setAddress($router->getClient()->getAdress());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($router);

            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            try {
                $cmd = new SshDhcpRegisterRouter($sshAdapter, $dhcpConfig, $router);
                $cmd->execute();
                $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $router);
                $dhcpScriptCmd->execute();
            } catch (SshExceptionInterface $ex) {
                throw new BadRequestHttpException($ex->getMessage());
            }

            $entityManager->flush();

            return $this->redirectToRoute('client_show_routers', [
                        'id' => $router->getClient()->getId()
            ]);
        }

        return $this->render('router/new.html.twig', [
                    'router' => $router,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="router_show", methods={"GET"})
     */
    public function show(Router $router): Response {
        return $this->render('router/show.html.twig', [
                    'router' => $router,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="router_edit", methods={"GET","POST"})
     */
    public function edit(
            Request $request,
            Router $router,
            DhcpConfigRepository $dhcpConfRep): Response {
        $form = $this->createForm(RouterType::class, $router, ['action' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dhcpConfig = $dhcpConfRep->getConfig();
            $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
            if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
                throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
            }
            $cmd = new SshDhcpUnregisterRouter($sshAdapter, $dhcpConfig, $router);
            $cmd->execute();
            if ($router->getActive()) {
                $cmd = new SshDhcpRegisterRouter($sshAdapter, $dhcpConfig, $router);
                $cmd->execute();
            }
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $router);
            $dhcpScriptCmd->execute();

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show_routers', [
                        'id' => $router->getClient()->getId()
            ]);
        }

        return $this->render('router/edit.html.twig', [
                    'router' => $router,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="router_delete", methods={"DELETE"})
     */
    public function delete(
            Request $request,
            Router $router,
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
            $cmd = new SshDhcpUnregisterRouter($sshAdapter, $dhcpConfig, $router);
            $cmd->execute();
            $dhcpScriptCmd = new SshDhcpScriptRestartCommand($sshAdapter, $dhcpConfig, $router);
            $dhcpScriptCmd->execute();
        } catch (SshExceptionInterface $ex) {
            throw new BadRequestHttpException($ex->getMessage());
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($router);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'ROUTER ' . $router . ' eliminado correctamente'
        ]);
    }

    /**
     * @Route("/{id}/show-log", name="router_show_log", methods={"GET"})
     */
    public function showLog(
            Request $request,
            Router $router,
            DhcpConfigRepository $dhcpConfRep): Response {

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }
        $cmd = new SshDhcpLogRouter($sshAdapter, $dhcpConfig, $router);
        $log = $cmd->execute();

        return $this->render('log/show_log.html.twig', [
                    'log' => $log
        ]);
    }

    /**
     * @Route("/{id}/remote-access", name="router_remote_access", methods={"GET"})
     */
    public function remoteAccess(
            Request $request,
            Router $router,
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
            $cmd = new SshDhcpGetIp($sshAdapter, $dhcpConfig, $router);
            $ip = $cmd->execute();
        } catch (SshExceptionInterface $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
                    ], Response::HTTP_BAD_REQUEST);
        }


        $protocol = $_ENV['ROUTER_HTTP_PROTOCOL'];
        $port = $_ENV['ROUTER_HTTP_PORT'];
        $httpCredential = $router->getHttpCredential();
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
