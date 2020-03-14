<?php

namespace App\Controller;

use App\Entity\SpecialCapacity;
use App\Form\SpecialCapacityType;
use App\Repository\SpecialCapacityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/capacity", name="capacity_")
 */
class SpecialCapacityController extends AbstractController
{
    private $manager;
    private $specialCapacityRepository;

    public function __construct(EntityManagerInterface $manager, SpecialCapacityRepository $specialCapacityRepository)
    {
        $this->manager = $manager;
        $this->specialCapacityRepository = $specialCapacityRepository;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $categories = $this->specialCapacityRepository->findAll();

        return $this->render('capacity/index.html.twig', [
            'entities' => $categories,
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @Route("/update/{id}", name="update")
     * @ParamConverter("capacity", options={"id"="id"})
     */
    public function add(Request $request, SpecialCapacity $capacity = null): Response
    {
        if($capacity === null){
            $capacity = new SpecialCapacity();
        }

        $form = $this->createForm(SpecialCapacityType::class, $capacity)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($capacity);
            $this->manager->flush();

            $this->addFlash('success', 'Your request has been successfully processed.');
        }

        return $this->render('capacity/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @ParamConverter("capacity", options={"id"="id"})
     */
    public function delete(SpecialCapacity $capacity): Response
    {
        $this->manager->remove($capacity);
        $this->manager->flush();

        return new Response($this->generateUrl('capacity_list'));
    }
}
