<?php

namespace App\Console\Commands;

use App\Helpers\ImageHelper;
use App\TicketGroup;
use App\TicketList;
use App\TourGroup;
use App\TourList;
use App\TransportationGroup;
use App\TransportationList;
use Illuminate\Console\Command;

class GenerateImageThumbs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:image:thumbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate image thumbs for all tours, transportations and tickets';

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
        foreach (TourList::all() as $obj)
        {
            self::generate($obj->image);
        }

        foreach (TourGroup::all() as $obj)
        {
            self::generate($obj->portrait_image);
        }

        foreach (TicketList::all() as $obj)
        {
            self::generate($obj->image);
        }

        foreach (TicketGroup::all() as $obj)
        {
            self::generate($obj->portrait_image);
        }

        foreach (TransportationList::all() as $obj)
        {
            self::generate($obj->image);
        }

        foreach (TransportationGroup::all() as $obj)
        {
            self::generate($obj->portrait_image);
        }
    }

    private function generate($path)
    {
        $this->info($path);
        ImageHelper::generateThumbnail($path);
    }
}
