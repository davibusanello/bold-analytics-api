<?php

namespace App\Console\Commands;

use App\Repositories\ShopifyReviewsApiRepository;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ShopifyReviewSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:review-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize the apps reviews';

    /**
     * List of apps to get reviews
     * @var array
     */
    protected $appsList = [
        'product-upsell',
        'product-discount',
        'store-locator',
        'product-options',
        'quantity-breaks',
        'product-bundles',
        'customer-pricing',
        'product-builder',
        'social-triggers',
        'recurring-orders',
        'multi-currency',
        'quickbooks-online',
        'xero',
        'the-bold-brain',
    ];

    /**
     * @var ShopifyReviewsApiRepository
     */
    private $apiRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $base_url =  env('SHOPIFY_BASE_URL');
        $this->apiRepository = new ShopifyReviewsApiRepository($base_url);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("Job start: {$this->signature} --- " . Carbon::create()->format('m-d-Y H:i:s'));
        $totalApps = count($this->appsList);

        foreach ($this->appsList as $indexApp => $appName) {
            $progressMessage = "[" . ($indexApp + 1) . "/{$totalApps}]";
            $this->line("$progressMessage  - Getting reviews from app: {$appName}");
            $this->apiRepository->getReviews($appName);
        }
        $syncedTotals = $this->apiRepository->getSyncTotal();
        $this->line("Total Reviews Synced - Created: {$syncedTotals['totalCreated']} | Updated: {$syncedTotals['totalUpdated']}");
        $this->line("Job end: {$this->signature} --- " . Carbon::create()->format('m-d-Y H:i:s'));
    }
}
