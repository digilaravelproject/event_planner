<?php

namespace App\Services;

use Illuminate\Support\Collection;

class TransactionExportService extends AdminUserExportService
{
    public function rows(Collection $transactions): array
    {
        return $transactions->map(fn ($transaction): array => [
            $transaction->id,
            $transaction->user_id,
            $transaction->user?->name ?: 'Deleted user',
            $transaction->user?->email ?: 'N/A',
            $transaction->user?->mobile_number ?: 'N/A',
            $transaction->plan?->name ?: 'Deleted plan',
            $transaction->plan?->durationLabel() ?: str($transaction->billing_cycle)->replace('_', ' ')->headline(),
            (float) $transaction->amount,
            $transaction->currency,
            ucfirst($transaction->status),
            $transaction->razorpay_order_id ?: 'N/A',
            $transaction->razorpay_payment_id ?: 'N/A',
            $transaction->starts_at?->format('d M Y H:i') ?: 'N/A',
            $transaction->ends_at?->format('d M Y H:i') ?: 'N/A',
            $transaction->paid_at?->format('d M Y H:i') ?: 'N/A',
            $transaction->created_at?->format('d M Y H:i') ?: '',
        ])->all();
    }

    public function headers(): array
    {
        return ['Transaction ID', 'User ID', 'User name', 'Email', 'Mobile', 'Plan', 'Duration', 'Amount', 'Currency', 'Status', 'Razorpay order ID', 'Razorpay payment ID', 'Starts at', 'Ends at', 'Paid at', 'Created at'];
    }

    public function pdf(Collection $transactions): string
    {
        $rows = $this->rows($transactions);
        $pages = array_chunk($rows, 22) ?: [[]];
        $streams = [];

        foreach ($pages as $pageIndex => $pageRows) {
            $stream = "0.98 0.98 0.99 rg 0 0 842 595 re f\n";
            $stream .= "0.12 0.18 0.35 rg 0 535 842 60 re f\n";
            $stream .= $this->text(28, 563, 18, 'F2', '1 1 1', 'PAYMENT TRANSACTIONS EXPORT');
            $stream .= $this->text(28, 544, 8, 'F1', '0.85 0.89 1', 'Generated '.now()->format('d M Y H:i').' | '.count($rows).' transactions');
            $columns = [
                ['label' => 'ID', 'width' => 35],
                ['label' => 'USER / EMAIL', 'width' => 240],
                ['label' => 'PLAN / DURATION', 'width' => 170],
                ['label' => 'AMOUNT', 'width' => 90],
                ['label' => 'STATUS', 'width' => 70],
                ['label' => 'PAYMENT / ORDER ID', 'width' => 197],
            ];
            $stream .= "0.23 0.31 0.64 rg 20 497 802 27 re f\n";
            $x = 20;
            foreach ($columns as $column) {
                $stream .= $this->cellText($x, 506, $column['width'], 7, 'F2', '1 1 1', $column['label']);
                $x += $column['width'];
            }
            $y = 483;

            foreach ($pageRows as $index => $row) {
                if ($index % 2 === 0) {
                    $stream .= '0.95 0.96 0.99 rg 20 '.($y - 8)." 802 21 re f\n";
                }
                $user = $this->limit($row[2].' / '.$row[3], 36);
                $plan = $this->limit($row[5].' / '.$row[6], 28);
                $gateway = $this->limit(($row[11] !== 'N/A' ? $row[11] : $row[10]), 23);
                $values = [$row[0], $user, $plan, 'Rs. '.number_format($row[7], 2), $row[9], $gateway];
                $x = 20;
                foreach ($columns as $columnIndex => $column) {
                    $stream .= $this->cellText($x, $y, $column['width'], 7, 'F1', '0.12 0.16 0.24', (string) $values[$columnIndex]);
                    $x += $column['width'];
                }
                $stream .= '0.86 0.88 0.92 RG 0.35 w 20 '.($y - 8).' m 822 '.($y - 8)." l S\n";
                $y -= 21;
            }

            $bottom = $y + 13;
            $x = 20;
            $stream .= "0.78 0.81 0.87 RG 0.45 w 20 {$bottom} 802 ".(524 - $bottom)." re S\n";
            foreach ($columns as $column) {
                $x += $column['width'];
                $stream .= "{$x} {$bottom} m {$x} 524 l S\n";
            }

            $stream .= $this->text(730, 22, 7, 'F1', '0.4 0.45 0.55', 'Page '.($pageIndex + 1).' of '.count($pages));
            $streams[] = $stream;
        }

        return $this->assemblePdf($streams);
    }

    protected function sheetName(): string
    {
        return 'Transactions';
    }
}
