<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * @Route("/client")
 * 
 */
class ClientController extends AbstractController {

    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response {
        return $this->render('client/index.html.twig', [
                    'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", message="No tiene permisos para realizar esta acción.")
     */
    public function new(Request $request): Response {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
                    'client' => $client,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response {
        return $this->render('client/show.html.twig', [
                    'client' => $client,
        ]);
    }
    
    /**
     * @Route("/{id}/antennas", name="client_show_antennas", methods={"GET"})
     */
    public function showAntenna(Client $client): Response {
        return $this->render('client/show_antennas.html.twig', [
                    'client' => $client,
        ]);
    }
    
    /**
     * @Route("/{id}/atas", name="client_show_atas", methods={"GET"})
     */
    public function showAtas(Client $client): Response {
        return $this->render('client/show_atas.html.twig', [
                    'client' => $client,
        ]);
    }
    
    /**
     * @Route("/{id}/routers", name="client_show_routers", methods={"GET"})
     */
    public function showRouters(Client $client): Response {
        return $this->render('client/show_routers.html.twig', [
                    'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", message="No tiene permisos para realizar esta acción.")
     */
    public function edit(Request $request, Client $client): Response {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
                    'client' => $client,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN", message="No tiene permisos para realizar esta acción.")
     */
    public function delete(Request $request, Client $client, EntityManagerInterface $em): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }

        try {
            $em->remove($client);
            $em->flush();   
        } catch (ForeignKeyConstraintViolationException $ex) {
            return new JsonResponse([
                'message' => 'Error al eliminar el cliente. Compruebe que no tenga ningún dispositivo asociado.'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $ex) {
            return new JsonResponse([
                'message' => $ex->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
        
        return new JsonResponse([
            'message' => 'Cliente ' . $client . ' eliminado correctamente'
        ]);
    }

}
