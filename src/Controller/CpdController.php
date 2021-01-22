<?php

namespace App\Controller;

use App\Entity\Cpd;
use App\Form\CpdType;
use App\Repository\CpdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/cpd")
 * @IsGranted("ROLE_SUPER_ADMIN", message="No tiene permisos para realizar esta acción.")
 */
class CpdController extends AbstractController {

    /**
     * @Route("/", name="cpd_index", methods={"GET"})
     */
    public function index(CpdRepository $cpdRepository): Response {
        return $this->render('cpd/index.html.twig', [
                    'cpds' => $cpdRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cpd_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $cpd = new Cpd();
        $form = $this->createForm(CpdType::class, $cpd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cpd);
            $entityManager->flush();

            return $this->redirectToRoute('cpd_index');
        }

        return $this->render('cpd/new.html.twig', [
                    'cpd' => $cpd,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cpd_show", methods={"GET"})
     */
    public function show(Cpd $cpd): Response {
        return $this->render('cpd/show.html.twig', [
                    'cpd' => $cpd,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cpd_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cpd $cpd): Response {
        $form = $this->createForm(CpdType::class, $cpd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cpd_index');
        }

        return $this->render('cpd/edit.html.twig', [
                    'cpd' => $cpd,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cpd_delete", methods={"DELETE"})
     */
    public function delete(
            Request $request, 
            Cpd $cpd, 
            EntityManagerInterface $em): Response {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed.");
        }
        
        $em->remove($cpd);
        $em->flush();

        return new JsonResponse([
            'message'   =>  'CPD '. $cpd. ' eliminado correctamente'
        ]);
    }

}
