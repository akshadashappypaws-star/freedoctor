<?php
namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    public function __construct(
        public string $href,
        public string $icon,
        public string $label,
        public bool $active = false
    ) {}

    public function render(): View|Closure|string
    {
        return view('admin.components.sidebarlink'); // your custom path
    }
}
