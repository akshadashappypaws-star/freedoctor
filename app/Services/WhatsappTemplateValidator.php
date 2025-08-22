<?php
namespace App\Services;

use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Log;

class WhatsappTemplateValidator
{
    /**
     * Validate if a template can be sent with given parameters
     */
    public static function validateTemplate($templateName, $parameters = [])
    {
        $template = WhatsappTemplate::where('name', $templateName)->first();
        
        if (!$template) {
            return [
                'valid' => false,
                'error' => "Template '{$templateName}' not found"
            ];
        }
        
        if ($template->status !== 'APPROVED') {
            return [
                'valid' => false,
                'error' => "Template '{$templateName}' is not approved (status: {$template->status})"
            ];
        }
        
        // Check if template requires parameters
        $requiredParams = self::getRequiredParameters($template);
        
        if (count($requiredParams) > 0) {
            // Template has parameters
            if (empty($parameters)) {
                return [
                    'valid' => false,
                    'error' => "Template '{$templateName}' requires " . count($requiredParams) . " parameters: " . implode(', ', $requiredParams),
                    'required_params' => $requiredParams
                ];
            }
            
            if (count($parameters) < count($requiredParams)) {
                return [
                    'valid' => false,
                    'error' => "Template '{$templateName}' requires " . count($requiredParams) . " parameters, but only " . count($parameters) . " provided",
                    'required_params' => $requiredParams,
                    'provided_params' => count($parameters)
                ];
            }
        }
        
        return [
            'valid' => true,
            'template' => $template,
            'required_params' => $requiredParams
        ];
    }
    
    /**
     * Extract required parameters from template components
     */
    public static function getRequiredParameters($template)
    {
        $requiredParams = [];
        
        if (!$template->components) {
            return $requiredParams;
        }
        
        $components = is_string($template->components) 
            ? json_decode($template->components, true) 
            : $template->components;
            
        foreach ($components as $component) {
            if (isset($component['text'])) {
                // Find parameter placeholders like {{1}}, {{2}}, etc.
                if (preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches)) {
                    foreach ($matches[1] as $paramNumber) {
                        $requiredParams[] = "{{$paramNumber}}";
                    }
                }
            }
            
            // Check for parameters in buttons
            if (isset($component['buttons'])) {
                foreach ($component['buttons'] as $button) {
                    if (isset($button['text']) && preg_match_all('/\{\{(\d+)\}\}/', $button['text'], $matches)) {
                        foreach ($matches[1] as $paramNumber) {
                            $requiredParams[] = "{{$paramNumber}}";
                        }
                    }
                }
            }
        }
        
        return array_unique($requiredParams);
    }
    
    /**
     * Get all templates with their parameter requirements
     */
    public static function getAllTemplatesWithParams()
    {
        $templates = WhatsappTemplate::where('status', 'APPROVED')->get();
        $result = [];
        
        foreach ($templates as $template) {
            $requiredParams = self::getRequiredParameters($template);
            $result[] = [
                'id' => $template->id,
                'name' => $template->name,
                'category' => $template->category,
                'language' => $template->language,
                'requires_params' => count($requiredParams) > 0,
                'param_count' => count($requiredParams),
                'required_params' => $requiredParams
            ];
        }
        
        return $result;
    }
    
    /**
     * Prepare template components with parameters for WhatsApp API
     */
    public static function prepareTemplateComponents($template, $parameters = [])
    {
        if (empty($parameters)) {
            return [];
        }
        
        $components = [];
        
        // Add BODY component with parameters
        if (count($parameters) > 0) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(function($param) {
                    return [
                        'type' => 'text',
                        'text' => $param
                    ];
                }, array_values($parameters))
            ];
        }
        
        return $components;
    }
}
?>
