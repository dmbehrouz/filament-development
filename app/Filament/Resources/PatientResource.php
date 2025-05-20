<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Filament\Resources\PatientResource\RelationManagers\TreatmentsRelationManager;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = 'اطلاعات بیماران';
    protected static ?string $label = 'اطلاعات بیماران';

    // Show/Hidden menu on sidebar
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('نوع')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->label('تاریخ تولد')
                    ->required()
                    ->maxDate(now()),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('تاریخ تولد شمسی')
                    ->jalali()
                    ->required(),
                Forms\Components\Select::make('owner_id')
                    ->label('صاحب')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('پست الکترونیک')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('تلفن')
                            ->tel()
                            ->required(),
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام بیمار')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('تاریخ تولد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('نام صاحابش')
                    ->searchable(),
            ])->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                    ]),
            ]);
    }

    // Relations of
    public static function getRelations(): array
    {
        return [
            TreatmentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

}
