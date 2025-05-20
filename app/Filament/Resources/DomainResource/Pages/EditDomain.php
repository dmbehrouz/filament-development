<?php

namespace App\Filament\Resources\DomainResource\Pages;

use App\Filament\Resources\DomainResource;
use App\Models\SubjectType;
use App\Scopes\ShowInDrawerScope;
use Filament\Actions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;

class EditDomain extends EditRecord
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @return array|Component[]
     */
    protected function getFormSchema(): array
    {
        $domain = $this->record;
        $allSubjects = SubjectType::withoutGlobalScope(ShowInDrawerScope::class)->select(['id','display_name', 'title'])->get()->toArray();
        $config = json_decode($domain->config,true);
        if ( isset($domain->config) && !empty($config['materialThemeSideMenu']) ) {
            $selectedDomainMenu = collect($config['materialThemeSideMenu'])->pluck('key')->toArray();
            $allSubjects = collect($allSubjects)->map(function ($item) use ($selectedDomainMenu) {
                $item['selected'] = in_array($item['title'], $selectedDomainMenu);
                return $item;
            })->sortBy(function ($item) use ($selectedDomainMenu) {
                return array_search($item['title'], $selectedDomainMenu);
            })->toArray();
        }

        return [
            Select::make('subject_type_id')
                ->label('منو های انتخابی')
                ->options($allSubjects->pluck('display_name', 'id'))
                ->multiple()
                ->searchable(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $config = json_decode($data['config'], true);
        $data['subject_type_id'] = collect($config['materialThemeSideMenu'] ?? [])->whereNotIn('key',['compare','monitoring'])->pluck('title')->toArray();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Retrieve default domain configuration
        $domainConfig = config('domain.default_domain_config');
        $permanentMenu = config('user.permanent_status_menu');

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
