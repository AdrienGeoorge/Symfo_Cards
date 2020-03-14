<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/card", name="card_")
 */
class CardController extends AbstractController
{
    private $manager;
    private $cardRepository;

    public function __construct(EntityManagerInterface $manager, CardRepository $cardRepository)
    {
        $this->manager = $manager;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $cards = $this->cardRepository->findAll();

        return $this->render('card/index.html.twig', [
            'entities' => $cards,
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @Route("/update/{id}", name="update")
     * @ParamConverter("card", options={"id"="id"})
     */
    public function add(Request $request, Card $card = null): Response
    {
        if($card === null){
            $card = new Card();
        }

        $filename = $card->getImage();

        $form = $this->createForm(CardType::class, $card)
            ->handleRequest($request);

        $card->setImage($filename);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                $image = $form->get('image')->getData();

            if ($image) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('cards_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new FileException('Error file');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $card->setImage($newFilename);
            }



            $card->setCreator($this->getUser());
            $this->manager->persist($card);
            $this->manager->flush();

            $this->addFlash('success', 'Your request has been successfully processed.');
            }else{
                return new Response('error');
            }
        }

        return $this->render('card/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @ParamConverter("card", options={"id"="id"})
     */
    public function delete(Card $card): Response
    {
        $this->manager->remove($card);
        $this->manager->flush();

        return new Response($this->generateUrl('card_list'));
    }
}
