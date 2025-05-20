<?php

namespace App\Filament\Resources\DomainResource\Pages;

use App\Filament\Resources\DomainResource;
use App\Models\SubjectType;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDomain extends CreateRecord
{
    protected static string $resource = DomainResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Retrieve default domain configuration
        $domainConfig = config('app.default_domain_config');
        $permanentMenu = config('app.permanent_status_menu');

        // Generate menu configuration based on selected menus
        $resultMenusConfig = SubjectType::createSubjectMenuConfig($data['menus'] ?? []);

        // Merge with permanent menus
        $resultMenusConfig = array_merge($resultMenusConfig, $permanentMenu);

        // Assign the menu configuration to the domain config
        $domainConfig['materialThemeSideMenu'] = $resultMenusConfig;

        // Update the domain name if 'appName' is provided
        if (!empty($data['appName'])) {
            $data['name'] = $data['appName'];
        }

        // Encode the configuration as JSON
        $data['config'] = json_encode($domainConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $data;
    }
}
