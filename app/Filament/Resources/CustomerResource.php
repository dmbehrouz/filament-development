<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

//    protected static ?string $navigationIcon = 'heroicon-o-document-text';
//    protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';
    protected static ?string $navigationGroup = 'مشتری';

    // Tooltip of menu badge
    protected static ?string $navigationBadgeTooltip = 'اطلاعات مشتری ها';
    protected static ?string $pluralLabel = 'مشتری ها';
    protected static ?string $label = 'مشتری ها';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
//            Section::make('اطلاعات تماس')
//                ->description('ایمیل و شماره‌ها')
//                ->schema([
//                    Forms\Components\TextInput::make('email'),
//                    Forms\Components\TextInput::make('phone'),
//                ])
//                ->columns(2),
            Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('نامک (انگلیسی)')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('display_name')
                        ->label('نام نمایشی')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('پست الکترونیک')
                        ->email()
                        ->required()
                        ->maxLength(255),
                ]),
            Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('phone')
                        ->label('تلفن')
                        ->tel()
                        ->nullable(),
                    Forms\Components\TextInput::make('mobile')
                        ->label('موبایل')
                        ->tel()
                        ->nullable(),
                    Forms\Components\Select::make('domains')
                        ->label('دامنه‌ها')
                        ->relationship('domains', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ]),
            Grid::make(1)
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('توضیحات')
                        ->nullable()
                        ->maxLength(255),
                ]),
            Forms\Components\FileUpload ::make('image')
                ->label('تصویر')
                ->nullable(),
//                Forms\Components\DatePicker::make('date_of_birth')
//                    ->label('تاریخ تولد')
//                    ->required()
//                    ->maxDate(now()),
//                Forms\Components\DateTimePicker::make('published_at')
//                    ->label('تاریخ تولد شمسی')
//                    ->jalali()
//                    ->required(),
//                Forms\Components\Select::make('owner_id')
//                    ->label('صاحب')
//                    ->relationship('owner', 'name')
//                    ->searchable()
//                    ->preload()
//                    ->createOptionForm([
//                        Forms\Components\TextInput::make('name')
//                            ->label('نام')
//                            ->required()
//                            ->maxLength(255),
//                        Forms\Components\TextInput::make('email')
//                            ->label('پست الکترونیک')
//                            ->email()
//                            ->required()
//                            ->maxLength(255),
//                        Forms\Components\TextInput::make('phone')
//                            ->label('تلفن')
//                            ->tel()
//                            ->required(),
//                    ])
//                    ->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('شناسه')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('نامک (‌انگلیسی)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display_name')
                    ->label('نام نمایشی'),

            ])->filters([

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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
