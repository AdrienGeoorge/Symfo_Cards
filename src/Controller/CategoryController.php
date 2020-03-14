<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    private $manager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {
        $this->manager = $manager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'entities' => $categories,
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @Route("/update/{id}", name="update")
     * @ParamConverter("category", options={"id"="id"})
     */
    public function add(Request $request, Category $category = null): Response
    {
        if($category === null){
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager->flush();

            $this->addFlash('success', 'Your request has been successfully processed.');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @ParamConverter("category", options={"id"="id"})
     */
    public function delete(Category $category): Response
    {
        $this->manager->remove($category);
        $this->manager->flush();

        return new Response($this->generateUrl('category_list'));
    }
}
