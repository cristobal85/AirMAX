<?php

namespace App\Controller;

use App\Entity\DhcpConfig;
use App\Form\DhcpConfigType;
use App\Repository\DhcpConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Checker\CheckDhcp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Ssh\Adapter\SshAdapter;
use App\DbGenerator\Exception\DbGeneratorExceptionInterface;
use \Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\DbGenerator\DbClientGenerator;
use App\DbGenerator\DbAntennaGenerator;
use App\DbGenerator\DbAtaGenerator;
use App\DbGenerator\DbRouterGenerator;

/**
 * @Route("/dhcp/config")
 * @IsGranted("ROLE_SUPER_ADMIN", message="No tiene permisos para realizar esta acción.")
 */
class DhcpConfigController extends AbstractController {

    /**
     * @Route("/", name="dhcp_config_index", methods={"GET"})
     */
    public function index(DhcpConfigRepository $dhcpConfigRepository): Response {
        return $this->render('dhcp_config/index.html.twig', [
                    'dhcp_configs' => $dhcpConfigRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="dhcp_config_show", methods={"GET"})
     */
    public function show(DhcpConfig $dhcpConfig): Response {
        return $this->render('dhcp_config/show.html.twig', [
                    'dhcp_config' => $dhcpConfig,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dhcp_config_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DhcpConfig $dhcpConfig, CheckDhcp $dhcpChecker): Response {
        $form = $this->createForm(DhcpConfigType::class, $dhcpConfig);
        $form->handleRequest($request);

        $dhcpStatus = $dhcpChecker->check($form);
        if (!$dhcpStatus->isOk()) {
            $this->addFlash('danger', $dhcpStatus);
        }
        if ($form->isSubmitted() && $form->isValid() && $dhcpStatus->isOk()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dhcp_config_index');
        }

        return $this->render('dhcp_config/edit.html.twig', [
                    'dhcp_config' => $dhcpConfig,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/import", name="dhcp_config_import", methods={"GET"})
     */
    public function importDataFromFile(
            Request $request,
            DhcpConfig $dhcpConfig,
            EntityManagerInterface $em,
            ValidatorInterface $validator): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        $sshAdapter = new SshAdapter($dhcpConfig->getHost(), $dhcpConfig->getPort());
        if (!$sshAdapter->login($dhcpConfig->getUsername(), $dhcpConfig->getPassword())) {
            return new JsonResponse([
                "message" => "No se pudo conectar al servidor DHCP a través de SSH."
                    ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $dbClientGenerator = new DbClientGenerator($sshAdapter, $validator);
            $dbClientGenerator->generate($dhcpConfig, $em);
            $dbAntennaGenerator = new DbAntennaGenerator($sshAdapter, $validator);
            $dbAntennaGenerator->generate($dhcpConfig, $em);
            $dbAtaGenerator = new DbAtaGenerator($sshAdapter, $validator);
            $dbAtaGenerator->generate($dhcpConfig, $em);
            $dbRouterGenerator = new DbRouterGenerator($sshAdapter, $validator);
            $dbRouterGenerator->generate($dhcpConfig, $em);
        } catch (DbGeneratorExceptionInterface $e) {
            return new JsonResponse([
                'message' => $e->getReason() . ' Mira el LOG del navegador para más información.',
                'log' => $e->getMessage()
                    ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
                    ], Response::HTTP_BAD_REQUEST);
        }

        
        return new JsonResponse([
            'message' => 'Datos importados correctamente.'
        ]);

    }

}
