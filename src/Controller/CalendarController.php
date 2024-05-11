<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use App\Entity\Calendar;
use App\Entity\Reservations;
use App\Entity\SallesTravail;
use App\Form\CalendarType;
use App\Form\FusionnerFormType;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationsRepository;
use App\Repository\SallesTravailRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar_index', methods: ['GET'])]
    public function index(CalendarRepository $calendarRepository, SallesTravailRepository $sallesTravailRepository, ReservationsRepository $reservationsRepository): Response
    {
        $calendars = $calendarRepository->findAll();
        $sallesTravail = $sallesTravailRepository->findAll();
        $reservations = $reservationsRepository->findAll();

        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendars,
            'sallesTravail' => $sallesTravail,
            'reservations' => $reservations
        ]);
    }

    #[Route('/reservations', name: 'app_calendar_admin_index', methods: ['GET'])]
    public function reservervations(CalendarRepository $calendarRepository, SallesTravailRepository $sallesTravailRepository, ReservationsRepository $reservationsRepository, UtilisateursRepository $utilisateursRepository): Response
    {
        $calendars = $calendarRepository->findAll();
        $sallesTravail = $sallesTravailRepository->findAll();
        $reservations = $reservationsRepository->findAll();
        $utilisateurs = $utilisateursRepository->findAll();

        return $this->render('calendar/reservations.html.twig', [
            'calendars' => $calendars,
            'sallesTravail' => $sallesTravail,
            'reservations' => $reservations,
            'utilisateurs' => $utilisateurs
        ]);
    }

    #[Route('/new/{id}', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id, SallesTravail $sallesTravail): Response
    {

            $formData = []; // Initialisez les données du formulaire si nécessaire

            $form = $this->createForm(FusionnerFormType::class, $formData);
            //$formData[]->remove('sallesTravail');
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Traitez les données soumises

                // Récupérez les données des formulaires fusionnés
                $formulaire1Data = $form->get('calendar')->getData();
                
                $formulaire2Data = $form->get('reservations')->getData();

                $entityManager->persist($formulaire1Data);
                $entityManager->persist($formulaire2Data);
                $entityManager->flush();

                return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('calendar/new.html.twig', [
                //'calendar' => $calendar,
                'form' => $form->createView(),
                'id' => $id,
                'nom' => $sallesTravail->getNom()
            ]);
    }

    #[Route('/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, Reservations $reservations, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($calendar);
            $entityManager->remove($reservations);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/api/event', name:'api_event', methods: ['GET'])]
    public function getEvents(CalendarRepository $calendarRepository): Response
    {
        $events = $calendarRepository->findAll();
        return $this->json($events);
    }

   
}
