<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Checker\CheckDB;
use App\Checker\CheckDhcp;
use App\Roles\Enum\Role;
use App\Filesystem\Adapter\FilesystemAdapter;
use App\ShellCommand\Install\ShellGenerateSchemaCommand;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\Cpd;
use App\Form\CpdType;
use \App\Entity\DhcpConfig;
use App\Form\DhcpConfigType;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\DbFormType;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpClient\Exception\ServerException;

/**
 * @Route("/install")
 */
class InstallController extends AbstractController {

    /**
     * @Route("/", name="install", methods={"GET"})
     */
    public function index() {
        $cpdForm = $this->createForm(CpdType::class, new Cpd());
        $dbForm = $this->createForm(DbFormType::class);
        $dhcpConfigForm = $this->createForm(DhcpConfigType::class, new DhcpConfig());
        $registrationForm = $this->createForm(RegistrationFormType::class, new User());
        return $this->render('install/index.html.twig', [
                    'cpdForm' => $cpdForm->createView(),
                    'dbForm' => $dbForm->createView(),
                    'dhcpConfigForm' => $dhcpConfigForm->createView(),
                    'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @Route("/check-bd", name="install_check_bd", methods={"POST"})
     */
    public function checkDb(Request $request, CheckDB $checkDb) {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }
        $dbForm = $this->createForm(DbFormType::class);
        $dbForm->handleRequest($request);

        $dbStatus = $checkDb->check($dbForm);

        if (!$dbStatus->isOk()) {
            return new Response($dbStatus, Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'message' => (string) $dbStatus
        ]);
    }

    /**
     * @Route("/check-dhcp", name="install_check_dhcp", methods={"POST"})
     */
    public function checkDhcp(Request $request, CheckDhcp $checkDhcp) {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }
        $dhcpForm = $this->createForm(DhcpConfigType::class);
        $dhcpForm->handleRequest($request);

        $dhcpStatus = $checkDhcp->check($dhcpForm);

        if (!$dhcpStatus->isOk()) {
            return new Response($dhcpStatus, Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'message' => (string) $dhcpStatus
        ]);
    }

    /**
     * @Route("/", name="install_post", methods={"POST"})
     */
    public function install(
            Request $request,
            CheckDB $checkDb,
            CheckDhcp $checkDhcp,
            FilesystemAdapter $fsAdapter,
            ShellGenerateSchemaCommand $generateShemaCmd,
            EntityManagerInterface $em) {

        $cpd = new Cpd();
        $dhcpConfig = new DhcpConfig();

        $cpdForm = $this->createForm(CpdType::class, $cpd)->handleRequest($request);
        $dbForm = $this->createForm(DbFormType::class)->handleRequest($request);
        $dhcpConfigForm = $this->createForm(DhcpConfigType::class, $dhcpConfig)->handleRequest($request);


        // Check services
        $state = [
            $checkDb->check($dbForm),
            $checkDhcp->check($dhcpConfigForm)
        ];
        foreach ($state as $status) {
            if (!$status->isOk()) {
                throw new ServerException($status);
            }
        }

        // Generate .env.local file
        try {
            $fsAdapter->checkProjectDirectory();
            $fsAdapter->touchEnvLocalFile();
            $fsAdapter->writeDbConfigInEnvLocalFile($dbForm);
        } catch (Exception $e) {
            throw new ServerException($e->getMessage());
        }

        // Generate schema database
        if (!$generateShemaCmd->execute()) {
            throw new ServerException("Error al generar el schema de la base de datos. Revise la configuración de conexión.");
        }

        // Create dynamic connection to 
        $emNew = EntityManager::create([
                    'driver' => 'pdo_mysql',
                    'user' => $dbForm->get('user')->getData(),
                    'password' => $dbForm->get('password')->getData(),
                    'dbname' => $dbForm->get('name')->getData()
                        ], $em->getConfiguration(), $em->getEventManager());
        
        // Create CPD
        $emNew->persist($cpd);

        // Create DHCP Config
        $emNew->persist($dhcpConfig);

        // Save into DB
        $emNew->flush();

        return $this->redirectToRoute('install_create_admin');
    }

    /**
     * @Route("/create-admin", name="install_create_admin", methods={"GET","POST"})
     */
    public function createAdminuser(
            Request $request,
            EntityManagerInterface $em,
            UserPasswordEncoderInterface $passwordEncoder,
            FilesystemAdapter $fsAdapter) {

        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user)->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $user
                    ->setPassword(
                            $passwordEncoder->encodePassword(
                                    $user,
                                    $registrationForm->get('plainPassword')->getData()
                            )
                    )
                    ->setRoles([Role::ROLE_USER, Role::ROLE_ADMIN, Role::ROLE_SUPER_ADMIN])
            ;
            $em->persist($user);
            $em->flush();
            
            $fsAdapter->writeInstalledInEnvLocalFile(true);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('install/create-admin.html.twig', [
                    'registrationForm' => $registrationForm->createView()
        ]);
    }

}
