<?php

namespace App\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class SettingsExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(protected array $settings) { }

    public function getGlobals(): array
    {
        return [
            'settings' => $this->settings,
        ];
    }
}
