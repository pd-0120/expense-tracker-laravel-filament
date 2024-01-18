<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Resources\TransactionCategoryResource\Pages;
use App\Filament\Resources\TransactionCategoryResource\RelationManagers;
use App\Models\TransactionCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionCategoryResource extends Resource
{
    protected static ?string $model = TransactionCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(25),
                Forms\Components\Select::make('type')->label('Category Type')->options(CategoryType::asSelectArray())->required(),
                Forms\Components\Select::make('transaction_category_id')
                ->label('Parent Category')
                ->options(TransactionCategory::whereNull('transaction_category_id')->get()->pluck('name', 'id'))
                ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")->searchable()->sortable(),
                TextColumn::make("ParentCategory.name")->label('Parent Category'),
                TextColumn::make("type")
                ->formatStateUsing(fn(string $state): string => CategoryType::getKey($state))
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                        CategoryType::Income => 'success',
                        CategoryType::Expense=> 'danger',
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransactionCategories::route('/'),
            // 'create' => Pages\CreateTransactionCategory::route('/create'),
            // 'edit' => Pages\EditTransactionCategory::route('/{record}/edit'),
        ];
    }
}
