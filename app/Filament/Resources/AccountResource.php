<?php

namespace App\Filament\Resources;

use App\Enums\AccountCategory;
use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountResource extends Resource
{

    protected static ?string $model = Account::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        $accountCategories = AccountCategory::asSelectArray();

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label("Account Name")->required()->maxLength(25),
                Forms\Components\Select::make('category')
                ->options($accountCategories)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $accountCategories = AccountCategory::asSelectArray();

        return $table
            ->columns([
                TextColumn::make("name")->searchable()->sortable(),
                TextColumn::make("category")
                ->formatStateUsing(fn(string $state): string => AccountCategory::getKey(intval($state)))
                ->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options($accountCategories),
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
            'index' => Pages\ListAccounts::route('/'),
            // 'create' => Pages\CreateAccount::route('/create'),
            // 'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
