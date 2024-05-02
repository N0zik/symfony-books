<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
     #[Route('/calendrier', name: 'app_calendrier')] 
    public function index(CalendarRepository $calendar)
    {
        $events = $calendar->findAll();
        $reservs = [];

        foreach ($events as $event) {
            $reservs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }
        $data = json_encode($reservs);

        return $this->render('calendrier/index.html.twig', [
            'data' => $data,
        ]);

    }
}
