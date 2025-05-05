<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingItemDetailResource\Pages;
use App\Filament\Resources\OutgoingItemDetailResource\RelationManagers;
use App\Models\OutgoingItemDetail;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutgoingItemDetailResource extends Resource
{
    protected static ?string $model = OutgoingItemDetail::class;

    protected static ?string $pluralModelLabel = "Detail Item Keluar";
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationGroup = "Item";
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('outgoing_items_receipt_number')
                    ->required()
                    ->relationship('outgoingItem', 'receipt_number')
                    ->label('Nomor Resi / Surat')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('receipt_number')
                            ->required()
                            ->label('Nomor Resi')
                            ->maxLength(255)
                            ->placeholder('Nomor Resi / Nomor Surat'),
                        TextInput::make('total_price')
                            ->required()
                            ->label('Total Harga')
                            ->numeric()
                            ->default(0)
                            ->placeholder('Total Harga Semua Barang'),
                        TextInput::make('buyer')
                            ->label('Nama Pembeli')
                            ->maxLength(255)
                            ->placeholder('Nama Pembeli / Perusahaan'),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->placeholder('Alamat Pembeli / Perusahaan'),
                        DatePicker::make('created_at')
                            ->required()
                            ->label('Tanggal')
                    ]),
                Select::make('item_id')
                    ->required()
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Nama Item') ,
                TextInput::make('quantity')
                    ->required()
                    ->label('Jumlah Item')
                    ->integer()
                    ->placeholder('Jumlah Item yang Keluar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('outgoingItem.receipt_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item.name')
                    ->label('Nama Item')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Jumlah Item'),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
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
            'index' => Pages\ListOutgoingItemDetails::route('/'),
            'create' => Pages\CreateOutgoingItemDetail::route('/create'),
            'edit' => Pages\EditOutgoingItemDetail::route('/{record}/edit'),
        ];
    }
}
