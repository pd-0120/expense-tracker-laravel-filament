<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Transaction;
use DB;

class ExpenseVsIncomeChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'expenseVsIncomeChart';
    protected int|string|array $columnSpan = 'full';

    public ?string $filter = "last_year";
    protected static ?string $pollingInterval = null;

    protected $dateRange = [];
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Income/Expense Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *php artisan make:filament-apex-charts BlogPostsChart
     * @return array
     */
    protected function getOptions(): array
    {
        $dateFormate = "%Y-%m";
        $creditData = [];
        $debitData = [];
        $labelData = [];

        $activeFilter = $this->filter;

        if (in_array($activeFilter, ['today', 'this_week', 'last_week', 'month', 'last_month'])) {
            $dateFormate = "%Y-%m-%d";
        }

        $raqQuery = DB::raw('date_format(date, "' . $dateFormate . '") as date, sum(if(type = "credit", amount, 0)) as credit, sum(if(type = "debit", amount, 0)) as debit');

        $transactions = Transaction::select($raqQuery);

        $transactions = $transactions->whereBetween('date', $this->getDateRange())->groupBy('date')->orderBy('date')->get();

        if (in_array($this->filter, ['year', 'last_year'])) {
            $transactions = $transactions->groupBy('date');
            foreach ($transactions as $key => $transaction) {
                $labelData[] = $key;
                $credit = 0;
                $debit = 0;

                if (count($transaction) > 0) {
                    foreach ($transaction as $tranData) {
                        $credit = $credit + $tranData->credit;
                        $debit = $debit + $tranData->debit;
                    }
                }
                $creditData[] = $credit;
                $debitData[] = $debit;
            }
        } else {
            $creditData = $transactions->map(fn($value) => $value->credit);
            $debitData = $transactions->map(fn($value) => $value->debit);
            $labelData = $transactions->map(fn($value) => $value->date);
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Income',
                    'data' => $creditData,
                ],
                [
                    'name' => 'Expense',
                    'data' => $debitData,
                ],
            ],
            'xaxis' => [
                'categories' => $labelData,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'colors' => ['#79ea86', '#e75757'],
        ];
    }

    protected function getDateRange(): array
    {
        return match ($this->filter) {
            'today' => [now(), now()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'last_week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            default => [now(), now()],
        };
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
}
