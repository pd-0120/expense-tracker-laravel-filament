<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use DB;
use Flowframe\Trend\TrendValue;
use Flowframe\Trend\Trend;


class TransactionChart extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Income Vs Expense Chart';
    public ?string $filter = "last_year";
    protected static ?string $pollingInterval = null;

    protected $dateRange = [];

    protected function getData(): array
    {
        $dateFormate = "%Y-%m";

        if(in_array($this->filter, ['today', 'this_week', 'last_week', 'month', 'last_month'])){
            $dateFormate = "%Y-%m-%d";
        }

        $raqQuery = DB::raw('date_format(date, "'.$dateFormate.'") as date, sum(if(type = "credit", amount, 0)) as credit, sum(if(type = "debit", amount, 0)) as debit');

        $transactions = Transaction::select($raqQuery);
        $transactions = $transactions->whereBetween('date', $this->getDateRange())->groupBy('date')->orderBy('date')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $transactions->map(fn($value) => $value->credit),
                    'backgroundColor' => '#79ea86',
                    'borderColor' => '#79ea86',
                ],
                [
                    'label' => 'Expense',
                    'data' => $transactions->map(fn($value) => $value->debit),
                    'backgroundColor' => '#e75757',
                    'borderColor' => '#e75757',
                ],
            ],
            'labels' => $transactions->map(fn($value) => $value->date),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'this_week' => 'This week',
            'last_week' => 'Last week',
            'month' => 'This month',
            'last_month' => 'Last month',
            'year' => 'This year',
            'last_year' => 'Last Year'
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getDateRange() : array {
        return match ($this->filter) {
            'today'     => [now(), now()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'last_week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'month'     => [now()->startOfMonth(), now()->endOfMonth()],
            'year'      => [now()->startOfYear(), now()->endOfYear()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            default     => [now(), now()],
        };
    }
}
