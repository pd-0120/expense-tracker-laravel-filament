<?php

namespace App\Filament\Resources;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public ?string $tableGroupingDirection = "desc";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        $transactionTypes = TransactionTypeEnum::asSelectArray();

        return $table
            ->columns([
                TextColumn::make('date'),
                TextColumn::make('fromAccount.name'),
                TextColumn::make('toAccount.name'),
                TextColumn::make('amount')->money('AUD')->summarize(Sum::make()),
                TextColumn::make('type')
                ->formatStateUsing(fn(string $state): string => TransactionTypeEnum::getKey($state))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        TransactionTypeEnum::Credit => 'success',
                        TransactionTypeEnum::Debit => 'danger',
                        TransactionTypeEnum::Transfer => 'warning',
                    })
            ])
            ->filters([
                SelectFilter::make('type')->options($transactionTypes)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('date')->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy('date', $direction))->date()->collapsible()
            ])
            ->defaultGroup('date')
            ->striped()
            ->defaultSort('date', 'asc');
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
