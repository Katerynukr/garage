<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Mechanick;
use App\Entity\Truck;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $mechanicks =  $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->findAll();
        
        $trucks = $this->getDoctrine()
        ->getRepository(Truck::class);

        if($r->query->get('filter') == 0 ){
            $trucks= $trucks->findAll();
            
        }else{
            $trucks = $trucks->findBy(['mechanic_id'=> $r->query->get('filter')]);
        }

        return $this->render('truck/index.html.twig', [
            'mechanicks' => $mechanicks,
            'trucks'=>$trucks,
            'mechanicID'=>$r->query->get('filter') ?? 0,
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

    /**
     * @Route("/truck/create", name="truck_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        
        $mechanicks =  $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->findAll();
        
        
        return $this->render('truck/create.html.twig', [
            'mechanicks' => $mechanicks,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

     /**
     * @Route("/truck/create", name="truck_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('truck_create');
        }

        $mechanick =  $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->find($r->request->get('truck_mechanick_id'));

        if($mechanick == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose mechanick from the list');
        }

        $truck = new Truck;

        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear((int)$r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanic($mechanick);

        $errors = $validator->validate($truck);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('truck_create');
        }
        if(null === $mechanick) {
            return $this->redirectToRoute('truck_create');
        }


        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($truck);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck was successfully created');

        return $this->redirectToRoute('truck_index');
    }

    /**
     * @Route("/truck/edit/{id}", name="truck_edit", methods= {"GET"})
     */
    public function edit(Request $r, int $id): Response
    {
        $truck= $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        $mechanicks = $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->findAll();
        
        return $this->render('truck/edit.html.twig', [
            'truck' => $truck,
            'mechanicks' => $mechanicks,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

     /**
     * @Route("/truck/update/{id}", name="truck_update", methods= {"POST"})
     */
    public function update(Request $r, int $id, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        $truck= $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('truck_edit' , ['id'=>$truck->getId()]);
        }
        
        $mechanick = $this->getDoctrine()
        ->getRepository(Mechanick::class)
        ->find($r->request->get('truck_mechanick_id'));

        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear((int)$r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanic($mechanick);

        $errors = $validator->validate($truck);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('truck_edit', ['id'=>$truck->getId()] );
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($truck);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck was successfully edited');

        return $this->redirectToRoute('truck_index');
    }

    /**
     * @Route("/truck/delete/{id}", name="truck_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('truck_index');
        }

        $truck= $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($truck);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck was successfully deleted');

        return $this->redirectToRoute('truck_index');
    }
}
