<?php

namespace App\Filament\Resources;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;


class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public ?string $tableGroupingDirection = "desc";

    public static function form(Form $form): Form
    {
        $accounts = Account::select('id','name')->get()->pluck('name', 'id')->toArray();
        $types = TransactionTypeEnum::toSelectArray();
        $transactionCategories = TransactionCategory::with('ChildCategories')->whereNull('transaction_category_id')->get();
        $categories = [];
        foreach($transactionCategories as $data) {
            if(count($data->ChildCategories) > 0) {
                $categories[$data['name']] = $data->ChildCategories->pluck('name', 'id')->toArray();
            } else {
                $categories[$data['name']] = [
                    $data['id'] => $data['name']
                ];
            }
        }

        return $form
            ->schema([
                DatePicker::make('date')->required()->native(false)->maxDate(now()->toDateString()),
                Hidden::make('user_id')->default(auth()->user()->id) ,
                Select::make('type')->label('Transaction Type')->required()->native(false)->options($types),
                Select::make('from_account_id')->label('From Account')->native(false)->required()->searchable()->options($accounts),
                Select::make('to_account_id')->label('To Account')->native(false)->required()->searchable()->options($accounts),
                Select::make('transaction_category_id')->label('Category')->native(false)->required()->searchable()->options($categories),
                TextInput::make('amount')->required()->numeric()->minValue(0),
                RichEditor::make('notes')->maxLength(200),
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
                TextColumn::make('amount')->money('AUD')->summarize(Sum::make())->color(fn($record) =>  match($record->type) {
                    TransactionTypeEnum::Credit => 'success',
                    TransactionTypeEnum::Debit => 'danger',
                    TransactionTypeEnum::Transfer => 'warning',
                }),
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
                SelectFilter::make('type')->options($transactionTypes),
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
