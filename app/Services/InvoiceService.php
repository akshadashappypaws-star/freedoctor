<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\PatientRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate PDF invoice for campaign registration
     *
     * @param Campaign $campaign
     * @param PatientRegistration $registration
     * @return string Path to generated PDF
     */
    public function generateInvoicePdf(Campaign $campaign, PatientRegistration $registration): string
    {
        $registrationNumber = $registration->registration_number ?? 'REG' . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
        
        $data = [
            'campaign' => $campaign,
            'registration' => $registration,
            'registrationNumber' => $registrationNumber,
            'invoiceNumber' => $invoiceNumber,
            'generatedDate' => Carbon::now(),
            'dueDate' => Carbon::now()->addDays(7),
        ];

        $pdf = Pdf::loadView('invoices.campaign-registration', $data);
        
        // Create invoices directory if it doesn't exist
        $invoicePath = 'invoices/' . date('Y/m');
        Storage::makeDirectory($invoicePath);
        
        // Generate filename
        $filename = $invoiceNumber . '_' . $registrationNumber . '.pdf';
        $fullPath = $invoicePath . '/' . $filename;
        
        // Save PDF to storage
        Storage::put($fullPath, $pdf->output());
        
        return storage_path('app/' . $fullPath);
    }

    /**
     * Generate QR code for campaign check-in
     *
     * @param Campaign $campaign
     * @param PatientRegistration $registration
     * @return string QR code data
     */
    public function generateQrCode(Campaign $campaign, PatientRegistration $registration): string
    {
        $registrationNumber = $registration->registration_number ?? 'REG' . str_pad($registration->id, 6, '0', STR_PAD_LEFT);
        
        $qrData = [
            'type' => 'campaign_checkin',
            'campaign_id' => $campaign->id,
            'registration_id' => $registration->id,
            'registration_number' => $registrationNumber,
            'patient_name' => $registration->patient_name,
            'phone' => $registration->phone,
            'timestamp' => time(),
        ];

        return base64_encode(json_encode($qrData));
    }

    /**
     * Calculate registration fees and taxes
     *
     * @param Campaign $campaign
     * @return array
     */
    public function calculateFees(Campaign $campaign): array
    {
        $baseFee = $campaign->registration_fee ?? 0;
        $serviceFee = $baseFee * 0.02; // 2% service fee
        $gst = ($baseFee + $serviceFee) * 0.18; // 18% GST
        $totalAmount = $baseFee + $serviceFee + $gst;

        return [
            'base_fee' => $baseFee,
            'service_fee' => $serviceFee,
            'gst' => $gst,
            'total_amount' => $totalAmount,
        ];
    }
}
