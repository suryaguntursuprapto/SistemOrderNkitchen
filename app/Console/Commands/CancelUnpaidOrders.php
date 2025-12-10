<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid {--hours=1 : Hours before cancellation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders that have not been paid within the specified time limit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        $this->info("Checking for unpaid orders older than {$hours} hour(s)...");

        // Find pending orders older than cutoff time
        $unpaidOrders = Order::where('status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->whereDoesntHave('payment', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->get();

        $cancelledCount = 0;

        foreach ($unpaidOrders as $order) {
            try {
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                               "[AUTO] Pesanan dibatalkan otomatis karena tidak dibayar dalam {$hours} jam."
                ]);

                // Update payment status if exists
                if ($order->payment) {
                    $order->payment->update([
                        'payment_status' => 'expired',
                    ]);
                }

                Log::info("Order #{$order->id} cancelled due to non-payment after {$hours} hours.");
                $cancelledCount++;

            } catch (\Exception $e) {
                Log::error("Failed to cancel order #{$order->id}: " . $e->getMessage());
                $this->error("Failed to cancel order #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Cancelled {$cancelledCount} unpaid order(s).");

        return Command::SUCCESS;
    }
}
