<?php

namespace App\Machines;

use Exception;

class DataTableMachine extends BaseMachine
{
    protected string $machineType = 'datatable';

    /**
     * Get default settings for DataTable machine
     */
    public static function getDefaultSettings(): array
    {
        return [
            'max_rows' => 1000,
            'pagination_size' => 50,
            'supported_formats' => ['whatsapp_list', 'table', 'json', 'csv', 'cards'],
            'default_format' => 'whatsapp_list',
            'sort_enabled' => true,
            'filter_enabled' => true,
            'search_enabled' => true,
            'export_enabled' => true,
            'cache_results' => true,
            'cache_duration' => 600,
            'template_engine' => 'blade',
            'responsive_design' => true
        ];
    }

    public function execute(array $input, int $stepNumber): array
    {
        return $this->safeExecute(function () use ($input) {
            $this->validateInput($input, ['action', 'data']);

            $action = $input['action'];
            $data = $input['data'];
            $options = $input['options'] ?? [];

            return $this->processData($action, $data, $options);
        }, $stepNumber, $input['action'] ?? 'unknown', $input);
    }

    /**
     * Process data based on action
     */
    private function processData(string $action, array $data, array $options): array
    {
        switch ($action) {
            case 'formatDoctorList':
                return $this->formatDoctorList($data, $options);
            
            case 'formatHealthCampList':
                return $this->formatHealthCampList($data, $options);
            
            case 'formatPatientRegistration':
                return $this->formatPatientRegistration($data, $options);
            
            case 'generateSummary':
                return $this->generateSummary($data, $options);
            
            case 'sortByDistance':
                return $this->sortByDistance($data, $options);
            
            case 'filterByAvailability':
                return $this->filterByAvailability($data, $options);
            
            case 'groupByCategory':
                return $this->groupByCategory($data, $options);
            
            case 'calculateTotals':
                return $this->calculateTotals($data, $options);
            
            case 'formatForWhatsApp':
                return $this->formatForWhatsApp($data, $options);
            
            default:
                throw new Exception("Unknown data processing action: {$action}");
        }
    }

    /**
     * Format doctor list for display
     */
    private function formatDoctorList(array $data, array $options): array
    {
        $format = $options['format'] ?? 'list';
        $maxItems = $options['max_items'] ?? 10;
        $includeDistance = $options['include_distance'] ?? true;

        $doctors = array_slice($data['doctors'] ?? [], 0, $maxItems);

        $formatted = [];
        foreach ($doctors as $index => $doctor) {
            $doctorInfo = [
                'position' => $index + 1,
                'name' => $doctor['name'],
                'specialty' => $doctor['specialty'],
                'experience' => $doctor['experience'] ? $doctor['experience'] . ' years' : 'Not specified',
                'fee' => $doctor['consultation_fee'] ? '₹' . number_format($doctor['consultation_fee']) : 'Contact for fees',
                'rating' => $doctor['rating'] ? $doctor['rating'] . '/5 ⭐' : 'Not rated yet',
                'availability' => $doctor['is_available'] ? '✅ Available' : '❌ Not available',
            ];

            if ($includeDistance && isset($doctor['distance_km'])) {
                $doctorInfo['distance'] = $doctor['distance_km'] . ' km away';
            }

            if ($format === 'whatsapp') {
                $doctorInfo = $this->formatDoctorForWhatsApp($doctorInfo);
            }

            $formatted[] = $doctorInfo;
        }

        return [
            'formatted_doctors' => $formatted,
            'total_shown' => count($formatted),
            'total_available' => count($data['doctors'] ?? []),
            'format' => $format,
            'search_summary' => $this->generateDoctorSearchSummary($data)
        ];
    }

    /**
     * Format health camp list for display
     */
    private function formatHealthCampList(array $data, array $options): array
    {
        $format = $options['format'] ?? 'list';
        $maxItems = $options['max_items'] ?? 10;
        $includeDistance = $options['include_distance'] ?? true;

        $camps = array_slice($data['health_camps'] ?? [], 0, $maxItems);

        $formatted = [];
        foreach ($camps as $index => $camp) {
            $campInfo = [
                'position' => $index + 1,
                'title' => $camp['title'],
                'category' => $camp['category'],
                'doctor' => $camp['doctor_name'],
                'date' => date('d M Y', strtotime($camp['start_date'])),
                'time' => $camp['start_time'] . ' - ' . $camp['end_time'],
                'location' => $camp['location'],
                'cost' => $camp['cost'] > 0 ? '₹' . number_format($camp['cost']) : 'Free',
                'discount' => $camp['discount_percentage'] ? $camp['discount_percentage'] . '% off' : null,
                'final_cost' => $camp['final_cost'] !== $camp['cost'] ? '₹' . number_format($camp['final_cost']) : null,
                'availability' => $camp['registration_open'] ? '✅ Open for registration' : '❌ Registration closed',
                'slots' => $camp['max_participants'] ? 
                    ($camp['max_participants'] - $camp['current_registrations']) . ' slots left' : 
                    'Unlimited slots'
            ];

            if ($includeDistance && isset($camp['distance_km'])) {
                $campInfo['distance'] = $camp['distance_km'] . ' km away';
            }

            if ($format === 'whatsapp') {
                $campInfo = $this->formatCampForWhatsApp($campInfo);
            }

            $formatted[] = $campInfo;
        }

        return [
            'formatted_camps' => $formatted,
            'total_shown' => count($formatted),
            'total_available' => count($data['health_camps'] ?? []),
            'format' => $format,
            'search_summary' => $this->generateCampSearchSummary($data)
        ];
    }

    /**
     * Format patient registration confirmation
     */
    private function formatPatientRegistration(array $data, array $options): array
    {
        $format = $options['format'] ?? 'confirmation';

        $registration = [
            'registration_id' => $data['registration_id'],
            'campaign_title' => $data['campaign_title'],
            'patient_name' => $data['patient_name'],
            'amount_due' => $data['amount_due'] > 0 ? '₹' . number_format($data['amount_due']) : 'Free',
            'status' => $this->formatRegistrationStatus($data['status']),
            'registration_date' => date('d M Y, h:i A', strtotime($data['registration_date'])),
            'payment_required' => $data['payment_required']
        ];

        if ($format === 'whatsapp') {
            $registration = $this->formatRegistrationForWhatsApp($registration);
        }

        return [
            'formatted_registration' => $registration,
            'format' => $format,
            'next_steps' => $this->getRegistrationNextSteps($data)
        ];
    }

    /**
     * Generate summary from data
     */
    private function generateSummary(array $data, array $options): array
    {
        $type = $options['type'] ?? 'general';

        switch ($type) {
            case 'doctors':
                return $this->generateDoctorsSummary($data);
            case 'camps':
                return $this->generateCampsSummary($data);
            case 'registrations':
                return $this->generateRegistrationsSummary($data);
            default:
                return ['summary' => 'Data processed successfully', 'items_count' => count($data)];
        }
    }

    /**
     * Sort data by distance
     */
    private function sortByDistance(array $data, array $options): array
    {
        $order = $options['order'] ?? 'asc'; // asc or desc

        $items = $data['doctors'] ?? $data['health_camps'] ?? $data;

        usort($items, function ($a, $b) use ($order) {
            $distanceA = $a['distance_km'] ?? 999999;
            $distanceB = $b['distance_km'] ?? 999999;

            if ($order === 'desc') {
                return $distanceB <=> $distanceA;
            }
            return $distanceA <=> $distanceB;
        });

        $result = $data;
        if (isset($data['doctors'])) {
            $result['doctors'] = $items;
        } elseif (isset($data['health_camps'])) {
            $result['health_camps'] = $items;
        } else {
            $result = $items;
        }

        return [
            'sorted_data' => $result,
            'sort_criteria' => 'distance',
            'sort_order' => $order,
            'total_items' => count($items)
        ];
    }

    /**
     * Filter by availability
     */
    private function filterByAvailability(array $data, array $options): array
    {
        $availableOnly = $options['available_only'] ?? true;

        $items = $data['doctors'] ?? $data['health_camps'] ?? $data;

        $filtered = array_filter($items, function ($item) use ($availableOnly) {
            if (isset($item['is_available'])) {
                return $availableOnly ? $item['is_available'] : !$item['is_available'];
            }
            if (isset($item['registration_open'])) {
                return $availableOnly ? $item['registration_open'] : !$item['registration_open'];
            }
            return true;
        });

        $result = $data;
        if (isset($data['doctors'])) {
            $result['doctors'] = array_values($filtered);
        } elseif (isset($data['health_camps'])) {
            $result['health_camps'] = array_values($filtered);
        } else {
            $result = array_values($filtered);
        }

        return [
            'filtered_data' => $result,
            'filter_criteria' => 'availability',
            'available_only' => $availableOnly,
            'filtered_count' => count($filtered),
            'original_count' => count($items)
        ];
    }

    /**
     * Group data by category
     */
    private function groupByCategory(array $data, array $options): array
    {
        $items = $data['doctors'] ?? $data['health_camps'] ?? $data;

        $grouped = [];
        foreach ($items as $item) {
            $category = $item['specialty'] ?? $item['category'] ?? 'Other';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $item;
        }

        return [
            'grouped_data' => $grouped,
            'categories' => array_keys($grouped),
            'category_counts' => array_map('count', $grouped),
            'total_categories' => count($grouped)
        ];
    }

    /**
     * Calculate totals and statistics
     */
    private function calculateTotals(array $data, array $options): array
    {
        $field = $options['field'] ?? 'cost';
        $items = $data['doctors'] ?? $data['health_camps'] ?? $data;

        $values = array_column($items, $field);
        $numericValues = array_filter($values, 'is_numeric');

        if (empty($numericValues)) {
            return [
                'total' => 0,
                'average' => 0,
                'min' => 0,
                'max' => 0,
                'count' => 0
            ];
        }

        return [
            'total' => array_sum($numericValues),
            'average' => round(array_sum($numericValues) / count($numericValues), 2),
            'min' => min($numericValues),
            'max' => max($numericValues),
            'count' => count($numericValues),
            'field' => $field
        ];
    }

    /**
     * Format data specifically for WhatsApp
     */
    private function formatForWhatsApp(array $data, array $options): array
    {
        $type = $options['type'] ?? 'list';
        $maxLength = $options['max_length'] ?? 1600; // WhatsApp message limit

        $formatted = '';
        
        if ($type === 'doctors' && isset($data['doctors'])) {
            $formatted = $this->formatDoctorsForWhatsApp($data['doctors']);
        } elseif ($type === 'camps' && isset($data['health_camps'])) {
            $formatted = $this->formatCampsForWhatsApp($data['health_camps']);
        } else {
            $formatted = $this->formatGenericForWhatsApp($data);
        }

        // Truncate if too long
        if (strlen($formatted) > $maxLength) {
            $formatted = substr($formatted, 0, $maxLength - 100) . "\n\n...and more. Type 'more' to see additional results.";
        }

        return [
            'whatsapp_message' => $formatted,
            'message_length' => strlen($formatted),
            'max_length' => $maxLength,
            'truncated' => strlen($formatted) > $maxLength
        ];
    }

    // Helper methods for WhatsApp formatting

    private function formatDoctorForWhatsApp(array $doctor): string
    {
        $text = "*{$doctor['position']}. Dr. {$doctor['name']}*\n";
        $text .= "🏥 {$doctor['specialty']}\n";
        $text .= "👨‍⚕️ {$doctor['experience']}\n";
        $text .= "💰 {$doctor['fee']}\n";
        $text .= "⭐ {$doctor['rating']}\n";
        if (isset($doctor['distance'])) {
            $text .= "📍 {$doctor['distance']}\n";
        }
        $text .= "{$doctor['availability']}\n\n";
        
        return $text;
    }

    private function formatCampForWhatsApp(array $camp): string
    {
        $text = "*{$camp['position']}. {$camp['title']}*\n";
        $text .= "🏥 {$camp['category']}\n";
        $text .= "👨‍⚕️ Dr. {$camp['doctor']}\n";
        $text .= "📅 {$camp['date']} ({$camp['time']})\n";
        $text .= "📍 {$camp['location']}\n";
        if (isset($camp['distance'])) {
            $text .= "🚗 {$camp['distance']}\n";
        }
        $text .= "💰 {$camp['cost']}\n";
        if ($camp['discount']) {
            $text .= "🎉 {$camp['discount']}\n";
        }
        $text .= "🎫 {$camp['slots']}\n";
        $text .= "{$camp['availability']}\n\n";
        
        return $text;
    }

    private function formatRegistrationForWhatsApp(array $registration): string
    {
        $text = "✅ *Registration Confirmed!*\n\n";
        $text .= "📋 *Registration ID:* {$registration['registration_id']}\n";
        $text .= "🏥 *Event:* {$registration['campaign_title']}\n";
        $text .= "👤 *Patient:* {$registration['patient_name']}\n";
        $text .= "💰 *Amount:* {$registration['amount_due']}\n";
        $text .= "📅 *Registered:* {$registration['registration_date']}\n";
        $text .= "📊 *Status:* {$registration['status']}\n";
        
        return $text;
    }

    private function formatDoctorsForWhatsApp(array $doctors): string
    {
        $text = "🏥 *Available Doctors*\n\n";
        foreach (array_slice($doctors, 0, 5) as $index => $doctor) {
            $text .= $this->formatDoctorForWhatsApp([
                'position' => $index + 1,
                'name' => $doctor['name'],
                'specialty' => $doctor['specialty'],
                'experience' => $doctor['experience'] ? $doctor['experience'] . ' years' : 'Not specified',
                'fee' => $doctor['consultation_fee'] ? '₹' . number_format($doctor['consultation_fee']) : 'Contact for fees',
                'rating' => $doctor['rating'] ? $doctor['rating'] . '/5 ⭐' : 'Not rated yet',
                'availability' => $doctor['is_available'] ? '✅ Available' : '❌ Not available',
                'distance' => isset($doctor['distance_km']) ? $doctor['distance_km'] . ' km away' : null,
            ]);
        }
        return $text;
    }

    private function formatCampsForWhatsApp(array $camps): string
    {
        $text = "🏕️ *Health Camps Available*\n\n";
        foreach (array_slice($camps, 0, 5) as $index => $camp) {
            $text .= $this->formatCampForWhatsApp([
                'position' => $index + 1,
                'title' => $camp['title'],
                'category' => $camp['category'],
                'doctor' => $camp['doctor_name'],
                'date' => date('d M Y', strtotime($camp['start_date'])),
                'time' => $camp['start_time'] . ' - ' . $camp['end_time'],
                'location' => $camp['location'],
                'cost' => $camp['cost'] > 0 ? '₹' . number_format($camp['cost']) : 'Free',
                'discount' => $camp['discount_percentage'] ? $camp['discount_percentage'] . '% off' : null,
                'slots' => $camp['max_participants'] ? 
                    ($camp['max_participants'] - $camp['current_registrations']) . ' slots left' : 
                    'Unlimited slots',
                'availability' => $camp['registration_open'] ? '✅ Open for registration' : '❌ Registration closed',
                'distance' => isset($camp['distance_km']) ? $camp['distance_km'] . ' km away' : null,
            ]);
        }
        return $text;
    }

    private function formatGenericForWhatsApp(array $data): string
    {
        return "📊 *Information*\n\n" . json_encode($data, JSON_PRETTY_PRINT);
    }

    // Helper methods for summaries

    private function generateDoctorSearchSummary(array $data): string
    {
        $total = $data['total_found'] ?? 0;
        $specialty = $data['specialty_filter'] ?? 'all specialties';
        $radius = $data['search_radius_km'] ?? 'unlimited';

        return "Found {$total} doctors in {$specialty} within {$radius}km radius";
    }

    private function generateCampSearchSummary(array $data): string
    {
        $total = $data['total_found'] ?? 0;
        $category = $data['category_filter'] ?? 'all categories';
        $radius = $data['search_radius_km'] ?? 'unlimited';

        return "Found {$total} health camps in {$category} within {$radius}km radius";
    }

    private function formatRegistrationStatus(string $status): string
    {
        $statusMap = [
            'pending_payment' => '⏳ Pending Payment',
            'confirmed' => '✅ Confirmed',
            'cancelled' => '❌ Cancelled',
            'completed' => '🎉 Completed'
        ];

        return $statusMap[$status] ?? $status;
    }

    private function getRegistrationNextSteps(array $data): array
    {
        if ($data['payment_required']) {
            return [
                'Make payment of ₹' . number_format($data['amount_due']),
                'Wait for confirmation',
                'Attend the health camp on scheduled date'
            ];
        }

        return [
            'Registration is confirmed',
            'Attend the health camp on scheduled date',
            'Bring valid ID proof'
        ];
    }

    private function generateDoctorsSummary(array $data): array
    {
        $doctors = $data['doctors'] ?? [];
        $available = array_filter($doctors, fn($d) => $d['is_available'] ?? false);
        
        return [
            'summary' => count($doctors) . ' doctors found, ' . count($available) . ' available',
            'total_doctors' => count($doctors),
            'available_doctors' => count($available),
            'specialties' => array_unique(array_column($doctors, 'specialty'))
        ];
    }

    private function generateCampsSummary(array $data): array
    {
        $camps = $data['health_camps'] ?? [];
        $open = array_filter($camps, fn($c) => $c['registration_open'] ?? false);
        
        return [
            'summary' => count($camps) . ' health camps found, ' . count($open) . ' open for registration',
            'total_camps' => count($camps),
            'open_for_registration' => count($open),
            'categories' => array_unique(array_column($camps, 'category'))
        ];
    }

    private function generateRegistrationsSummary(array $data): array
    {
        return [
            'summary' => 'Registration processed successfully',
            'registration_id' => $data['registration_id'] ?? null,
            'status' => $data['status'] ?? 'unknown'
        ];
    }
}
