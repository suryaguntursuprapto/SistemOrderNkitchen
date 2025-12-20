<?php

namespace App\Console\Commands;

use App\Services\BiteshipService;
use Illuminate\Console\Command;

class BiteshipCancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biteship:cancel {order_id : Biteship Order ID to cancel} {--reason= : Cancellation reason}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel a Biteship order by ID (for testing API activation)';

    /**
     * Execute the console command.
     */
    public function handle(BiteshipService $biteshipService)
    {
        $orderId = $this->argument('order_id');
        $reason = $this->option('reason') ?? 'Cancelled for API activation testing';

        $this->info("Cancelling Biteship order: {$orderId}");
        $this->info("Reason: {$reason}");
        $this->newLine();

        $result = $biteshipService->cancelOrder($orderId, $reason);

        if ($result && $result['success']) {
            $this->info('✅ Order cancelled successfully!');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Order ID', $orderId],
                    ['Status', 'Cancelled'],
                ]
            );
        } else {
            $this->error('❌ Failed to cancel order');
            $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
        }

        return 0;
    }
}
