<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\DhcpConfigRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Ssh\Adapter\SshAdapter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Ssh\Command\Dhcp\SshDhcpLogSystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/log")
 * @IsGranted("ROLE_SUPER_ADMIN", message="No tiene permisos para realizar esta acción.")
 */
class LogController extends AbstractController {

    /**
     * @Route("/", name="system_log")
     */
    public function index() {
        return $this->render('log/index.html.twig', [
                    'controller_name' => 'LogController',
        ]);
    }
    
    /**
     * @Route("/show-log", name="system_log_show", methods={"GET"})
     */
    public function showLog(
            Request $request,
            DhcpConfigRepository $dhcpConfRep): Response {

        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $dhcpConfig = $dhcpConfRep->getConfig();
        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            throw new BadRequestHttpException('Error al conectar al servidor DHCP via SSH.');
        }
        $cmd = new SshDhcpLogSystem($sshAdapter, $dhcpConfig);
        $log = $cmd->execute();

        return $this->render('log/show_log.html.twig', [
                    'log' => $log
        ]);
    }

}
