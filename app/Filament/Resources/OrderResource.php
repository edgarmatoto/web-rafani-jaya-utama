<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource;
use App\Filament\Resources\OrderResource\Pages\CreateOrder;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\Invoice;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Item;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $pluralModelLabel = 'Order / Item Keluar';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),

                        Forms\Components\Section::make('Order items')
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Pembeli')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_discount_amount')
                    ->label('Potongan')
                    ->getStateUsing(fn (Order $record) => $record->items->sum('discount_amount') ?? 0)
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->searchable()
                    ->sortable()
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR'),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_invoice')
                    ->label('Lihat Resi')
                    ->icon('heroicon-o-document')
                    ->url(fn (Order $record) => route('order.invoice.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
//            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    /** @return Builder<Order> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['receipt_number', 'customer_name'];
    }

    /** @return Builder<Order> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['items']);
    }

    /** @return Forms\Components\Component[] */
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('receipt_number')
                ->default('OR-' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->label('Nomor Surat / Resi')
                ->unique(Order::class, 'receipt_number', ignoreRecord: true),

            Forms\Components\TextInput::make('customer_name')
                ->label('Nama / Toko Pembeli')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('customer_phone')
                ->label('No. Telepon')
                ->maxLength(255),

            Forms\Components\TextInput::make('customer_address')
                ->label('Alamat Pembeli')
                ->required()
                ->maxLength(255),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->label('Nama Item')
                    ->options(Item::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->preload()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $quantity = $get('qty');
                        $discount_amount = $get('discount_amount') ?? 0;

                        if ($state && $quantity) {
                            $item = Item::find($state);
                            if ($item) {
                                $set('subtotal', max(($quantity * $item->price) - $discount_amount, 0));
                            }
                        }
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 4,
                    ])
                    ->searchable(),

                Forms\Components\TextInput::make('qty')
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->label('Jumlah Item')
                    ->integer()
                    ->reactive()
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $itemId = $get('item_id');
                        $discount_amount = $get('discount_amount') ?? 0;

                        if ($itemId) {
                            $item = Item::find($itemId);
                            if ($item) {
                                $set('subtotal', max(($state * $item->price) - $discount_amount, 0));
                            }
                        }
                    }),

                Forms\Components\TextInput::make('discount_amount')
                    ->label('Potongan')
                    ->default(0)
                    ->reactive()
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $itemId = $get('item_id');
                        $quantity = $get('qty') ?? 1;

                        if ($itemId) {
                            $item = Item::find($itemId);
                            if ($item) {
                                $basedSubtotal = $item->price * $quantity;
                                $discountAmount = $state ?? 0;

                                $set('subtotal', max($basedSubtotal - $discountAmount, 0));
                            }
                        }
                    }),

                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->columnSpan([
                        'md' => 2,
                    ]),
            ])
            ->extraItemActions([
                Action::make('openItem')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $item = Item::find($itemData['item_id']);

                        if (! $item) {
                            return null;
                        }

                        return ItemResource::getUrl('edit', ['record' => $item]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['item_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])
            ->required()
            ->deletable(false)
            ->reorderable(false)
            ->reorderableWithDragAndDrop(false);
    }
}
