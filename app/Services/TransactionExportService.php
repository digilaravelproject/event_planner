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
            $stream .= "0.23 0.31 0.64 rg 20 500 802 24 re f\n";
            $stream .= $this->text(25, 508, 7.2, 'F2', '1 1 1', 'ID   USER / EMAIL                         PLAN / DURATION              AMOUNT       STATUS     PAYMENT / ORDER ID');
            $y = 483;

            foreach ($pageRows as $index => $row) {
                if ($index % 2 === 0) {
                    $stream .= '0.95 0.96 0.99 rg 20 '.($y - 8)." 802 21 re f\n";
                }
                $user = $this->limit($row[2].' / '.$row[3], 36);
                $plan = $this->limit($row[5].' / '.$row[6], 28);
                $gateway = $this->limit(($row[11] !== 'N/A' ? $row[11] : $row[10]), 23);
                $line = sprintf('%-4s %-36s %-28s Rs. %-8s %-10s %-23s', $row[0], $user, $plan, number_format($row[7], 2), $row[9], $gateway);
                $stream .= $this->text(25, $y, 7, 'F1', '0.12 0.16 0.24', $line);
                $y -= 21;
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
