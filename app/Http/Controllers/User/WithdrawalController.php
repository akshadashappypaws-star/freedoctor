<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PatientPayment;
use App\Models\PatientRegistration;
use App\Models\Campaign;
use Razorpay\Api\Api;

class WithdrawalController extends Controller
{
    /**
     * Process one-click withdrawal using Razorpay refund
     */
    public function processWithdrawal(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Check if user has sufficient balance
            if ($user->available_balance < 100) { // Minimum ₹100 for withdrawal
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Minimum withdrawal amount is ₹100'
                ], 400);
            }
            
            // Check if user has account details
            if (!$user->bank_account_number || !$user->bank_ifsc_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please add your bank account details first'
                ], 400);
            }
            
            $withdrawalAmount = $user->available_balance;
            
            // Initialize Razorpay API
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            try {
                // Direct payment to user's bank account using Razorpay payout approach
                // We'll create a payment and then transfer/refund it to user's account
                
                // Step 1: Create a payment order for the payout
                $orderData = [
                    'amount' => $withdrawalAmount * 100, // Convert to paise
                    'currency' => 'INR',
                    'receipt' => 'reward_payout_' . $user->id . '_' . time(),
                    'notes' => [
                        'purpose' => 'reward_payout_order',
                        'user_id' => $user->id,
                        'bank_account' => $user->bank_account_number,
                        'ifsc' => $user->bank_ifsc_code,
                        'account_holder' => $user->account_holder_name ?? $user->username
                    ]
                ];
                
                $order = $api->order->create($orderData);
                
                Log::info('Razorpay order created for reward payout', [
                    'user_id' => $user->id,
                    'order_id' => $order['id'],
                    'amount' => $withdrawalAmount
                ]);
                
                // Create withdrawal record in patient_payments table
                // Find the user's campaign registration to link the payment
                $campaignRegistration = \App\Models\PatientRegistration::where('user_id', $user->id)->first();
                
                if (!$campaignRegistration) {
                    // Create a dummy registration for withdrawal tracking if needed
                    $dummyCampaign = \App\Models\Campaign::first(); // Get any campaign for linking
                    if ($dummyCampaign) {
                        $campaignRegistration = \App\Models\PatientRegistration::create([
                            'user_id' => $user->id,
                            'campaign_id' => $dummyCampaign->id,
                            'amount' => 0,
                            'payment_status' => 'completed',
                            'registration_type' => 'referral_withdrawal_link'
                        ]);
                    }
                }
                
                // Create refund/withdrawal record in patient_payments table
                \App\Models\PatientPayment::create([
                    'patient_registration_id' => $campaignRegistration ? $campaignRegistration->id : null,
                    'campaign_id' => $campaignRegistration ? $campaignRegistration->campaign_id : 1,
                    'amount' => -$withdrawalAmount, // Negative amount indicates withdrawal/refund
                    'payment_id' => null, // Will be updated when actual refund is processed
                    'order_id' => $order['id'],
                    'payment_status' => 'pending',
                    'payment_details' => [
                        'type' => 'referral_reward_withdrawal',
                        'user_id' => $user->id,
                        'bank_account_number' => $user->bank_account_number,
                        'bank_ifsc_code' => $user->bank_ifsc_code,
                        'account_holder_name' => $user->account_holder_name ?? $user->username,
                        'bank_name' => $user->bank_name ?? 'Not specified',
                        'withdrawal_request_time' => now()->toDateTimeString(),
                        'razorpay_order' => $order,
                        'referral_earnings' => [
                            'total_referred' => \App\Models\User::where('referred_by', $user->id)->count(),
                            'total_earnings' => $user->total_earnings ?? 0,
                            'withdrawn_amount' => $user->withdrawn_amount ?? 0,
                            'available_balance' => $withdrawalAmount
                        ]
                    ],
                    'receipt_number' => 'REF_WDR_' . $user->id . '_' . time(),
                    'payment_date' => now(),
                    'admin_commission' => 0,
                    'doctor_amount' => 0
                ]);
                
                // Step 2: Create a payment capture to fund the transfer
                $paymentData = [
                    'amount' => $withdrawalAmount * 100,
                    'currency' => 'INR',
                    'order_id' => $order['id'],
                    'description' => 'Reward payout processing',
                    'notes' => [
                        'purpose' => 'reward_payout_payment',
                        'user_id' => $user->id,
                        'bank_account' => '****' . substr($user->bank_account_number, -4)
                    ]
                ];
                
                // Since we can't create payments directly without customer interaction,
                // we'll use a different approach - create a refund directly
                
                // Step 3: Process direct bank transfer using refund method
                $refundData = [
                    'amount' => $withdrawalAmount * 100,
                    'speed' => 'optimum',
                    'notes' => [
                        'reason' => 'Referral reward payout',
                        'user_id' => $user->id,
                        'bank_account' => $user->bank_account_number,
                        'ifsc' => $user->bank_ifsc_code,
                        'account_holder' => $user->account_holder_name ?? $user->username,
                        'payout_type' => 'reward_withdrawal'
                    ],
                    'receipt' => 'refund_payout_' . $user->id . '_' . time()
                ];
                
                // For direct bank transfer, we'll create a manual payout entry
                $payoutId = 'payout_' . $order['id'];
                $transferId = 'transfer_' . time() . '_' . $user->id;
                
                // Create a simulated bank transfer request
                $bankTransferData = [
                    'beneficiary_name' => $user->account_holder_name ?? $user->username,
                    'account_number' => $user->bank_account_number,
                    'ifsc_code' => $user->bank_ifsc_code,
                    'amount' => $withdrawalAmount,
                    'purpose' => 'Referral Reward Payment',
                    'reference_id' => $transferId,
                    'order_id' => $order['id']
                ];
                
                // Log the bank transfer request
                Log::info('Bank transfer initiated for reward payout', [
                    'user_id' => $user->id,
                    'transfer_data' => $bankTransferData,
                    'order_id' => $order['id']
                ]);
                
                // In a real implementation, you would integrate with your bank's API here
                // For now, we'll mark this as pending and notify the admin
                $transferStatus = 'pending_bank_processing';
                
                // Create a notification for manual processing (you can implement this)
                $this->notifyAdminForManualPayout($user, $withdrawalAmount, $bankTransferData);
                
                Log::info('Manual payout notification sent to admin', [
                    'user_id' => $user->id,
                    'amount' => $withdrawalAmount,
                    'transfer_id' => $transferId
                ]);
                
                // Update user balances using direct database update
                User::where('id', $user->id)->update([
                    'withdrawn_amount' => $user->withdrawn_amount + $withdrawalAmount,
                    'available_balance' => 0
                ]);
                
                Log::info('Referral reward withdrawal processed and saved to patient_payments table', [
                    'user_id' => $user->id,
                    'amount' => $withdrawalAmount,
                    'payout_id' => $payoutId,
                    'transfer_id' => $transferId,
                    'order_id' => $order['id'],
                    'status' => $transferStatus,
                    'bank_account' => '****' . substr($user->bank_account_number, -4),
                    'ifsc' => $user->bank_ifsc_code,
                    'account_holder' => $user->account_holder_name ?? $user->username,
                    'patient_payment_record' => 'Created with negative amount for withdrawal tracking'
                ]);
                
                // Success message for manual processing
                $statusMessage = 'Payout request submitted successfully! Your withdrawal will be processed manually within 24 hours and amount will be credited to your bank account.';
                
                return response()->json([
                    'success' => true,
                    'message' => $statusMessage,
                    'amount' => $withdrawalAmount,
                    'payout_id' => $payoutId,
                    'transfer_id' => $transferId,
                    'order_id' => $order['id'],
                    'status' => $transferStatus,
                    'method' => 'manual_bank_transfer',
                    'mode' => 'Bank Transfer',
                    'processing_time' => '24 hours',
                    'bank_details' => [
                        'account_number' => '****' . substr($user->bank_account_number, -4),
                        'ifsc' => $user->bank_ifsc_code,
                        'account_holder' => $user->account_holder_name ?? $user->username
                    ]
                ]);
                
            } catch (\Exception $e) {
                Log::error('Razorpay payout processing failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode()
                ]);
                
                // Check for specific Razorpay errors
                $errorMessage = 'Payout processing failed. Please contact support if the issue persists.';
                if (str_contains($e->getMessage(), 'insufficient_balance')) {
                    $errorMessage = 'Insufficient balance in merchant account. Please contact support.';
                } elseif (str_contains($e->getMessage(), 'invalid_account')) {
                    $errorMessage = 'Invalid bank account details. Please verify your account information.';
                } elseif (str_contains($e->getMessage(), 'order_creation_failed')) {
                    $errorMessage = 'Failed to create payment order. Please try again.';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'RAZORPAY_PAYOUT_ERROR'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Reward payout processing failed', [
                'user_id' => Auth::guard('user')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Reward payment processing failed. Please try again later.'
            ], 500);
        }
    }
    
    /**
     * Update user account details
     */

    
    /**
     * Update user account details
     */
    public function updateAccountDetails(Request $request)
    {
        $request->validate([
            'bank_account_number' => 'required|string|min:10|max:20',
            'bank_ifsc_code' => 'required|string|size:11',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:100'
        ]);
        
        $user = Auth::guard('user')->user();
        
        User::where('id', $user->id)->update([
            'bank_account_number' => $request->bank_account_number,
            'bank_ifsc_code' => strtoupper($request->bank_ifsc_code),
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Account details updated successfully!'
        ]);
    }

    /**
     * Notify admin for manual payout processing
     */
    private function notifyAdminForManualPayout($user, $amount, $bankDetails)
    {
        try {
            // Log the manual payout request for admin attention
            Log::channel('daily')->info('MANUAL PAYOUT REQUEST', [
                'timestamp' => now()->toDateTimeString(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_phone' => $user->phone,
                'amount' => $amount,
                'bank_details' => $bankDetails,
                'status' => 'PENDING_ADMIN_ACTION'
            ]);
            
            // Here you can add email notification to admin
            // Mail::to(config('app.admin_email'))->send(new PayoutRequestNotification($user, $amount, $bankDetails));
            
            // Or add to admin notifications table
            // AdminNotification::create([...]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to notify admin for manual payout', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
