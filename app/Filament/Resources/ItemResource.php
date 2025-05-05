<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $pluralModelLabel = 'Semua Item';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Item';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stok'),
                TextColumn::make('price')
                    ->label('Harga per Item'),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
