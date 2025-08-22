<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Admin;
use App\Models\UserMessage;
use App\Models\DoctorMessage;
use App\Models\AdminMessage;
use Illuminate\Http\JsonResponse;

class NotificationTestController extends Controller
{
    /**
     * Test user notification
     */
    public function testUserNotification($userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
            
            UserMessage::create([
                'user_id' => $user->id,
                'type' => 'business_request',
                'message' => 'Test notification: Your business request has been received and is being reviewed.',
                'data' => json_encode([
                    'request_id' => 'TEST-' . time(),
                    'organization_name' => 'Test Organization',
                    'specialty' => 'General Medicine',
                    'test' => true
                ]),
                'read' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => "Test notification created for user: {$user->name}",
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test doctor notification
     */
    public function testDoctorNotification($doctorId): JsonResponse
    {
        try {
            $doctor = Doctor::findOrFail($doctorId);
            
            DoctorMessage::create([
                'doctor_id' => $doctor->id,
                'type' => 'business_request',
                'message' => 'Test notification: New business opportunity matching your specialty!',
                'data' => json_encode([
                    'request_id' => 'TEST-' . time(),
                    'organization_name' => 'Test Organization',
                    'specialty' => $doctor->specialty ?? 'General Medicine',
                    'location' => 'Test City',
                    'test' => true
                ]),
                'read' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => "Test notification created for doctor: {$doctor->name}",
                'doctor_id' => $doctor->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test admin notification
     */
    public function testAdminNotification($adminId): JsonResponse
    {
        try {
            $admin = Admin::findOrFail($adminId);
            
            AdminMessage::create([
                'admin_id' => $admin->id,
                'type' => 'proposal',
                'message' => 'Test notification: New doctor proposal received for review.',
                'data' => json_encode([
                    'proposal_id' => 'TEST-' . time(),
                    'doctor_name' => 'Dr. Test Doctor',
                    'organization_name' => 'Test Organization',
                    'specialty' => 'General Medicine',
                    'test' => true
                ]),
                'read' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => "Test notification created for admin: {$admin->name}",
                'admin_id' => $admin->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test notifications for all user types
     */
    public function testAllNotifications(): JsonResponse
    {
        try {
            $results = [];

            // Create test user notification
            $user = User::first();
            if ($user) {
                UserMessage::create([
                    'user_id' => $user->id,
                    'type' => 'approval',
                    'message' => 'Test: Your business request has been approved! A doctor will contact you soon.',
                    'data' => json_encode([
                        'request_id' => 'TEST-USER-' . time(),
                        'doctor_name' => 'Dr. Test Doctor',
                        'test' => true
                    ]),
                    'read' => false
                ]);
                $results['user'] = "Notification created for user: {$user->name}";
            }

            // Create test doctor notification
            $doctor = Doctor::first();
            if ($doctor) {
                DoctorMessage::create([
                    'doctor_id' => $doctor->id,
                    'type' => 'approval',
                    'message' => 'Test: Your proposal has been approved! You can now contact the organization.',
                    'data' => json_encode([
                        'proposal_id' => 'TEST-DOCTOR-' . time(),
                        'organization_name' => 'Test Health Organization',
                        'test' => true
                    ]),
                    'read' => false
                ]);
                $results['doctor'] = "Notification created for doctor: {$doctor->name}";
            }

            // Create test admin notification
            $admin = Admin::first();
            if ($admin) {
                AdminMessage::create([
                    'admin_id' => $admin->id,
                    'type' => 'system',
                    'message' => 'Test: System notification - All notification systems are working properly.',
                    'data' => json_encode([
                        'system_check' => 'TEST-ADMIN-' . time(),
                        'status' => 'operational',
                        'test' => true
                    ]),
                    'read' => false
                ]);
                $results['admin'] = "Notification created for admin: {$admin->name}";
            }

            return response()->json([
                'success' => true,
                'message' => 'Test notifications created for all user types',
                'results' => $results,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test notifications: ' . $e->getMessage()
            ], 500);
        }
    }
}
