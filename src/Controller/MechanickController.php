<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mechanick;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MechanickController extends AbstractController
{
     #[Route('/mechanick', name: 'mechanick_index', methods: ['GET'])]
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mechanicks = $this->getDoctrine()
        ->getRepository(Mechanick::class);
        
        if($r->query->get('sort_by') == 'sort_by_name_asc'){
            $mechanicks = $mechanicks->findBy([],['name' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_name_desc'){
            $mechanicks = $mechanicks->findBy([],['name' => 'desc']);
        }elseif($r->query->get('sort_by') == 'sort_by_surname_asc'){
            $mechanicks = $mechanicks->findBy([],['surname' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_surname_desc'){
            $mechanicks = $mechanicks->findBy([],['surname' => 'desc']);
        }else{
            $mechanicks = $mechanicks->findAll();
        }
        
        return $this->render('mechanick/index.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'mechanicks' => $mechanicks,
            'sortBy' => $r->query->get('sort_by') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

     /**
     * @Route("/mechanick/create", name="mechanick_create", methods= {"GET"})
     */
    public function create(Request $r): Response
    {
        $mechanick_name = $r->getSession()->getFlashBag()->get('mechanick_name', []);
        $mechanick_surname = $r->getSession()->getFlashBag()->get('mechanick_surname', []);

        return $this->render('mechanick/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'mechanick_name' => $mechanick_name[0] ?? '',
            'mechanick_surname' => $mechanick_surname[0] ?? '',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

     /**
     * @Route("/mechanick/store", name="mechanick_store", methods= {"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('mechanick_create');
        }

        $mechanick = new Mechanick;
        $mechanick->
        setName($r->request->get('mechanick_name'))->
        setSurname($r->request->get('mechanick_surname'));

        $errors = $validator->validate($mechanick);

        // dd(count($errors));
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('mechanick_name', $r->request->get('mechanick_name'));
            $r->getSession()->getFlashBag()->add('mechanick_surname', $r->request->get('mechanick_surname'));
            return $this->redirectToRoute('mechanick_create');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($mechanick);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Mechanic was successfully created');

        return $this->redirectToRoute('mechanick_index');
    }

    /**
     * @Route("/mechanick/edit/{id}", name="mechanick_edit", methods= {"GET"})
     */
    public function edit(Request $r, int $id): Response
    {
        $mechanick = $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->find($id);
        
        return $this->render('mechanick/edit.html.twig', [
            'mechanick' => $mechanick,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

     /**
     * @Route("/mechanick/update/{id}", name="mechanick_update", methods= {"POST"})
     */
    public function update(Request $r, int $id, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        $mechanick = $this->getDoctrine()
        ->getRepository(Mechanick ::class)
        ->find($id);

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('mechanick_edit', ['id'=>$mechanick->getId()]);
        }

        $mechanick->
        setName($r->request->get('mechanick_name'))->
        setSurname($r->request->get('mechanick_surname'));

        $errors = $validator->validate($mechanick);

        // dd(count($errors));
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('mechanick_name', $r->request->get('mechanick_name'));
            $r->getSession()->getFlashBag()->add('mechanick_surname', $r->request->get('mechanick_surname'));
            return $this->redirectToRoute('mechanick_edit', ['id'=>$mechanick->getId()]);
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($mechanick);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Mechanic was successfully edited');

        return $this->redirectToRoute('mechanick_index');
    }

    /**
     * @Route("/mechanick/delete/{id}", name="mechanick_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('mechanick_create');
        }

        $mechanick = $this->getDoctrine()
        ->getRepository(Mechanick ::class)
        ->find($id);

        if ($mechanick->getTrucks()->count() > 0) {
            $r->getSession()->getFlashBag()->add('errors', 'You cannot deleate the mechanic because it has trucks' );
            return $this->redirectToRoute('mechanick_index');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($mechanick);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Mechanic was successfully deleted');

        return $this->redirectToRoute('mechanick_index');
    }
}
