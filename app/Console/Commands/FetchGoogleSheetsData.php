<?php

namespace App\Console\Commands;

use App\Clients\GoogleSheetClient;
use App\Services\DataItemService;
use Illuminate\Console\Command;

class FetchGoogleSheetsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:fetch {count? : Number of rows to display}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Google Sheets and display model ID and comments';

    private $googleSheetClient;
    private $dataService;

    public function __construct(GoogleSheetClient $googleSheetClient, DataItemService $dataService)
    {
        parent::__construct();
        $this->googleSheetClient = $googleSheetClient;
        $this->dataService = $dataService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = $this->argument('count');

        $this->info('Fetching data from Google Sheets...');
        $sheetId = $this->dataService->getDocumentId();
        $data = $this->googleSheetClient->fetchData($sheetId,$count);

        if (empty($data)) {
            $this->error('No data found or Google Sheets not configured.');
            return 1;
        }

        $this->info('Found ' . count($data) . ' rows of data.');

        // Create progress bar
        $progressBar = $this->output->createProgressBar(count($data));
        $progressBar->start();

        $this->newLine();
        $this->info('Displaying data:');
        $this->newLine();

        foreach ($data as $index => $row) {
            if ($index === 0) {
                $progressBar->advance();
                continue;
            }

            $modelId = $row[0] ?? 'N/A';
            $comment = $row[4] ?? 'No comment';

            $this->line("Model ID: {$modelId} | Comment: {$comment}");

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('Data fetch completed successfully!');

        return 0;
    }
}
