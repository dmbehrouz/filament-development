<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';
    protected static ?string $pluralLabel = 'دامنه ها';
    protected static ?string $label = 'دامنه ها';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label("شرح ویزیت"),
                Tables\Columns\TextColumn::make('price')
                    ->label("هزینه")
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("تاریخ")
                    ->dateTime(),
            ]);
    }
}
