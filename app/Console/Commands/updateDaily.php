<?php

namespace App\Console\Commands;

use App\Models\Bilik;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class updateDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Hunian & Pembayaran';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Start executing updateDaily command.');

        $currentDate = Carbon::now()->format('Y-m-d');
        $tableBRecords = Pembayaran::where('bulan_end_terbayar', $currentDate)->get();

        if ($tableBRecords->isNotEmpty()) {
            foreach ($tableBRecords as $tableBRecord) {
                $bilikId = $tableBRecord->bilik_id;
        
                Bilik::where('bilik_id', $bilikId)
                    ->update([
                        'status_hunian' => 'Pending',
                        'status_pembayaran' => 'Belum bayar'
                    ]);
        
            }
        }
        echo "Update Successfull";

        Log::info('Finish executing updateDaily command.');  
    }
}
