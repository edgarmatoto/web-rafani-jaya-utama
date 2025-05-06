<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingItemResource\Pages;
use App\Models\IncomingItem;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncomingItemResource extends Resource
{
    protected static ?string $model = IncomingItem::class;

    protected static ?string $pluralModelLabel = "Item Masuk";
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationGroup = "Item";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')
                    ->required()
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('code')
                            ->required()
                            ->label("Kode")
                            ->maxLength(20)
                            ->placeholder('Kode Item'),
                        TextInput::make('name')
                            ->required()
                            ->label('Nama')
                            ->maxLength(255)
                            ->placeholder('Nama Item'),
                        TextInput::make('stock')
                            ->required()
                            ->label('Stok')
                            ->integer()
                            ->maxLength(255)
                            ->placeholder('Stok Item'),
                        TextInput::make('price')
                            ->required()
                            ->label('Harga')
                            ->numeric()
                            ->placeholder('Harga Item'),
                    ])
                    ->placeholder('Pilih Item (daftarkan item dahulu jika belum ada)'),
                TextInput::make('qty')
                    ->required()
                    ->label('Jumlah Item Masuk')
                    ->integer()
                    ->placeholder('Masukkan jumlah item yang masuk')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')
                    ->label('Nama Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('qty')
                    ->label('Jumlah Item Masuk'),
                TextColumn::make('created_at')
                    ->label('Tanggal Penerimaan')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListIncomingItems::route('/'),
            'create' => Pages\CreateIncomingItem::route('/create'),
//            'edit' => Pages\EditIncomingItem::route('/{record}/edit'),
        ];
    }
}
