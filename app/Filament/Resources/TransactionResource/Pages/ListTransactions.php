<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All'),
            TransactionTypeEnum::Credit => ListRecords\Tab::make()->query(fn($query) => $query->where('type', TransactionTypeEnum::Credit)),
            TransactionTypeEnum::Debit => ListRecords\Tab::make()->query(fn($query) => $query->where('type', TransactionTypeEnum::Debit)),
            TransactionTypeEnum::Transfer => ListRecords\Tab::make()->query(fn($query) => $query->where('type', TransactionTypeEnum::Transfer)),
        ];
    }
}
