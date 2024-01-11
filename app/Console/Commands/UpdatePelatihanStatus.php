<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelatihan;
use Carbon\Carbon;

class UpdatePelatihanStatus extends Command
{
    protected $signature = 'update:pelatihanstatus';

    protected $description = 'Update status of Pelatihan based on start_date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $pelatihan = Pelatihan::where('start_date', Carbon::today())
            ->where('status', 'Not started yet')
            ->update(['status' => 'On going']);

        $this->info('Pelatihan status updated successfully.');
    }
}
