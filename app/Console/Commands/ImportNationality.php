<?php

namespace App\Console\Commands;

use App\Nationality;
use Illuminate\Console\Command;
use Excel;

class ImportNationality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:nationality';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = storage_path('app/nationality.xlsx');
        Excel::load($file, function($reader) {

            $results = $reader->all();
            foreach ($results as $result)
            {
                $nationality = new Nationality();
                $nationality->{Nationality::COLUMN_EN} = $result['en'];
                $nationality->{Nationality::COLUMN_ZH_HK} = $result['zh_hk'];
                $nationality->{Nationality::COLUMN_ZH_CN} = $result['zh_cn'];
                $nationality->save();
            }

        });
    }
}
