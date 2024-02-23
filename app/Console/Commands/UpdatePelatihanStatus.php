<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelatihan;
use Carbon\Carbon;

class UpdatePelatihanStatus extends Command
{
    protected $signature = 'update:pelatihanstatus';

    protected $description = 'Update status of Pelatihan based on start_date and end_date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Perbarui status pelatihan yang mulai hari ini menjadi "On going"
        Pelatihan::where('start_date', Carbon::today())
            ->where('status', 'Not started yet')
            ->update(['status' => 'On going']);

        // Perbarui status pelatihan yang berakhir hari ini menjadi "Completed"
        Pelatihan::where('end_date', Carbon::today())
            ->where('status', 'On going')
            ->update(['status' => 'Completed']);

        $this->info('Pelatihan status updated successfully.');
    }
}
