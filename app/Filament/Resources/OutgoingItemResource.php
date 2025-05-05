<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingItemResource\Pages;
use App\Models\Item;
use App\Models\OutgoingItem;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class OutgoingItemResource extends Resource
{
    protected static ?string $model = OutgoingItem::class;

    protected static ?string $pluralModelLabel = "Item Keluar";
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationGroup = "Item";
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Detail')
                        ->schema([
                            TextInput::make('receipt_number')
                                ->required()
                                ->label('Nomor Resi')
                                ->maxLength(254)
                                ->placeholder('Nomor Resi / Nomor Surat'),
                            TextInput::make('buyer')
                                ->label('Nama Pembeli')
                                ->maxLength(254)
                                ->placeholder('Nama Pembeli / Perusahaan'),
                            TextInput::make('address')
                                ->label('Alamat')
                                ->maxLength(254)
                                ->placeholder('Alamat Pembeli / Perusahaan'),
                            DatePicker::make('created_at')
                                ->required()
                                ->label('Tanggal')
                        ]),
                    Wizard\Step::make('Item yang Keluar')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Repeater::make('outgoingItemDetail')
                                        ->relationship()
                                        ->required()
                                        ->schema([
                                            TextInput::make('outgoing_items_receipt_number')
                                                ->default(fn (Forms\Get $get) => $get('../../receipt_number')) // navigasi keluar dari repeater ke parent
                                                ->disabled()
                                                ->dehydrated()
                                                ->hidden(),
                                            Select::make('item_id')
                                                ->required()
                                                ->relationship('item', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->label('Nama Item')
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                    $quantity = $get('quantity');
                                                    if ($state && $quantity) {
                                                        $item = Item::find($state);
                                                        if ($item) {
                                                            $set('subtotal', $quantity * $item->price);
                                                        }
                                                    }
                                                }),
                                            TextInput::make('quantity')
                                                ->required()
                                                ->label('Jumlah Item')
                                                ->integer()
                                                ->reactive()
                                                ->placeholder('Jumlah Item yang Keluar')
                                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                    $itemId = $get('item_id');
                                                    if ($itemId) {
                                                        $item = Item::find($itemId);
                                                        if ($item) {
                                                            $set('subtotal', $state * $item->price);
                                                        }
                                                    }
                                                }),
                                            TextInput::make('subtotal')
                                                ->numeric()
                                                ->disabled()
                                                ->dehydrated()
                                                ->required()
                                        ])
                                ])
                        ]),
                ])
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button type="submit" size="sm">Submit</x-filament::button>
                    BLADE)))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Harga'),
                TextColumn::make('buyer')
                    ->label('Nama Pembeli')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Alamat'),
                TextColumn::make('created_at')
                    ->label('Tanggal'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOutgoingItems::route('/'),
            'create' => Pages\CreateOutgoingItem::route('/create'),
            'edit' => Pages\EditOutgoingItem::route('/{record}/edit'),
        ];
    }
}
