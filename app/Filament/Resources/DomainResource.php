<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Resources\DomainResource\RelationManagers;
use App\Models\Domain;
use App\Models\SubjectType;
use App\Scopes\ShowInDrawerScope;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationGroup = 'مشتری';
    protected static ?string $navigationBadgeTooltip = 'اطلاعات دامنه ها';
    protected static ?string $pluralLabel = 'دامنه ها';
    protected static ?string $label = 'دامنه ها';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // Not show because is sub-menu

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()
                ->schema([
                    Fieldset::make('تنظیمات اصلی')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('نامک (انگلیسی)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('domain')
                            ->label('آدرس دامنه')
                            ->required()
                            ->maxLength(255),
                        Grid::make(1)
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->label('توضیحات')
                                    ->nullable()
                                    ->maxLength(255),
                            ]),
                    ]),
                ]),
            Grid::make()
                ->schema([
                    Fieldset::make('تنظیمات ظاهری')->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\Select::make('domain_template_id')
                                    ->label('قالب')
                                    ->relationship('template', 'name')
                                    ->preload(),
                                Forms\Components\Select::make('menus')
                                    ->label('منو های انتخابی')
                                    ->options(function () {
                                        return SubjectType::withoutGlobalScope(ShowInDrawerScope::class)
                                            ->select(['id','display_name', 'title'])
                                            ->orderBy('priority' , 'DESC')->get()->pluck('display_name', 'id')->toArray();
                                    })->multiple()
                                ->searchable(),
                                Forms\Components\Select::make('menus')
                                    ->label('منو های انتخابی')
                                    ->options(function () {
                                        return SubjectType::withoutGlobalScope(ShowInDrawerScope::class)
                                            ->select(['id', 'display_name', 'title'])
                                            ->orderBy('priority', 'DESC')
                                            ->get()
                                            ->pluck('display_name', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                            ]),
                    ]),
                ]),

            Grid::make()->schema([Fieldset::make('تصاویر')
                ->schema([
                    Forms\Components\FileUpload ::make('logo')
                        ->label('لوگو')
                        ->nullable(),
                    Forms\Components\FileUpload ::make('image')
                        ->label('تصویر زمینه')
                        ->nullable(),
                ])])

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('شناسه')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->label('آدرس دامنه'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('نامک'),
                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => 'حذف دامنه (( ' . $record->name . " ))")
                    ->modalDescription('آیا مطمئن هستید می‌خواهید این دامنه را حذف کنید؟')
                    ->modalSubmitActionLabel('بله، حذف کن')
                    ->modalIcon('heroicon-o-trash')
                    ->modalIconColor('danger')
                    ->successNotificationTitle(fn ($record) => ' دامنه (( ' . $record->name . " )) حذف شد")
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
