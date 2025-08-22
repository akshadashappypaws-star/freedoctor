<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowMachineConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_type',
        'config_name',
        'config_key',
        'config_value',
        'config_type',
        'config_json',
        'description',
        'is_active',
        'priority',
        'version'
    ];

    protected $casts = [
        'config_json' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get active configurations for a machine type
     */
    public static function getActiveConfigs(string $machineType): array
    {
        return self::where('machine_type', $machineType)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get specific configuration
     */
    public static function getConfig(string $machineType, string $configName): ?array
    {
        $config = self::where('machine_type', $machineType)
            ->where('config_name', $configName)
            ->where('is_active', true)
            ->first();

        return $config ? $config->config_json : null;
    }

    /**
     * Set configuration
     */
    public static function setConfig(
        string $machineType,
        string $configName,
        array $configJson,
        int $priority = 0,
        string $version = '1.0'
    ): self {
        return self::updateOrCreate(
            [
                'machine_type' => $machineType,
                'config_name' => $configName
            ],
            [
                'config_json' => $configJson,
                'is_active' => true,
                'priority' => $priority,
                'version' => $version
            ]
        );
    }

    /**
     * Deactivate configuration
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Activate configuration
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }
}
