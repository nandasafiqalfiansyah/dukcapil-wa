<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\WhatsAppUser;
use App\Models\ConversationLog;
use App\Models\DocumentValidation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => WhatsAppUser::count(),
            'verified_users' => WhatsAppUser::verified()->count(),
            'pending_requests' => ServiceRequest::pending()->count(),
            'total_requests' => ServiceRequest::count(),
            'pending_validations' => DocumentValidation::pending()->count(),
            'conversations_today' => ConversationLog::whereDate('created_at', today())->count(),
            'escalated_requests' => ServiceRequest::escalated()->count(),
        ];

        $recentRequests = ServiceRequest::with(['whatsappUser', 'assignedOfficer'])
            ->latest()
            ->take(10)
            ->get();

        $requestsByStatus = ServiceRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $requestsByType = ServiceRequest::selectRaw('service_type, count(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');

        return view('admin.dashboard', compact(
            'stats',
            'recentRequests',
            'requestsByStatus',
            'requestsByType'
        ));
    }
}
