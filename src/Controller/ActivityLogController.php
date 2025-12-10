<?php

namespace App\Controller;

use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activity-logs')]
class ActivityLogController extends AbstractController
{
    #[Route('/', name: 'app_activity_logs', methods: ['GET'])]
    public function index(Request $request, ActivityLogRepository $activityLogRepository): Response
    {
        $action = $request->query->get('action');
        $role = $request->query->get('role');
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');

        $logs = $activityLogRepository->findByFilters($action, $role, $dateFrom, $dateTo);

        return $this->render('activity_log/index.html.twig', [
            'logs' => $logs,
            'filters' => [
                'action' => $action,
                'role' => $role,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }
}
