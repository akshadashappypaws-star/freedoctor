<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WhatsappTemplate;
use App\Models\WhatsappConversation;
use App\Services\WhatsAppCloudApiService;
use Illuminate\Support\Facades\Cache;

class WhatsAppStatusMiddleware
{
    protected $whatsappService;

    public function __construct(WhatsAppCloudApiService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin/whatsapp*')) {
            // Check WhatsApp connection status
            $isConnected = $this->checkWhatsAppConnection();
            
            // Get template count
            $templateCount = Cache::remember('whatsapp_template_count', 3600, function () {
                return WhatsappTemplate::count();
            });
            
            // Get messages sent today
            $messagesToday = Cache::remember('whatsapp_messages_today', 300, function () {
                return WhatsappConversation::whereDate('created_at', today())->count();
            });

            // Share data with all views
            session([
                'whatsapp_connected' => $isConnected,
                'template_count' => $templateCount,
                'messages_today' => $messagesToday,
                'whatsapp_error' => session('whatsapp_error')
            ]);
        }

        return $next($request);
    }

    protected function checkWhatsAppConnection()
    {
        try {
            $status = Cache::remember('whatsapp_connection_status', 300, function () {
                return $this->whatsappService->checkConnection();
            });
            
            return $status;
        } catch (\Exception $e) {
            session(['whatsapp_error' => $e->getMessage()]);
            return false;
        }
    }
}
