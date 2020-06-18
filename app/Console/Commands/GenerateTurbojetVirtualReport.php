<?php

namespace App\Console\Commands;

use App\Order;
use App\OrderProduct;
use App\OrderProductTurbojet;
use App\TurbojetReserve;
use App\TurbojetVirtualReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Excel;
use DB;

class GenerateTurbojetVirtualReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:turbojet:virtual-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Turbojet Virtual Report';

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
        try
        {
            \DB::beginTransaction();

            $order_products = OrderProduct::where(OrderProduct::COLUMN_TYPE, OrderProduct::TYPE_COMBO)
                ->where(OrderProduct::COLUMN_STATUS, '!=', OrderProduct::STATUS_IN_QUEUE)
                ->whereNull(OrderProduct::COLUMN_VIRTUAL_REPORT_GENERATED_AT)
                ->get();

            $reservations = [];
            foreach ($order_products as $order_product)
            {
                if (!$order_product->turbojet_reserve)
                    continue;

                foreach ($order_product->turbojet_reserve->getReservationTable() as $reservation)
                {
                    $routes = [
                        'HKGMAC' => 'H',
                        'MACHKG' => 'M',
                        'HKGYFT' => 'G',
                        'YFTHKG' => 'K',
                        'KLNMAC' => 'Z',
                        'MACKLN' => 'Y',
                        'TFTMAC' => 'N',
                        'MACTFT' => 'A',
                        'TFTYFT' => '8',
                        'YFTTFT' => '7',
                    ];

                    $route = $reservation['from-code'] . $reservation['to-code'];

                    if (!array_key_exists($route, $routes))
                        continue;

                    if ($reservation['class-code'] == OrderProductTurbojet::CLASS_ECONOMY)
                        $seat_class = "E";
                    else if ($reservation['class-code'] == OrderProductTurbojet::CLASS_SUPER)
                        $seat_class = "S";
                    else
                        $seat_class = "P";


                    $reservations[] = [
                        'transaction-id' => $order_product->turbojet_reserve->{TurbojetReserve::COLUMN_TRANSACTION_ID},
                        'reservation-no' => $reservation['reservation-no'],
                        'route' => $routes[$route],
                        'time' => $reservation['time'],
                        'class' => $seat_class,
                        'promo-code' => $reservation['promotion-code'],
                        'collector' => $order_product->name,
                    ];
                }

                $order_product->{OrderProduct::COLUMN_VIRTUAL_REPORT_GENERATED_AT} = Carbon::now();
                $order_product->save();
            }

            $date = Carbon::now()->addDay(-1);
            $filename = "turbojet-virtual-report-" . $date->format("Y-m-d");

            Excel::create($filename, function ($excel) use ($reservations, $date) {
                $excel->sheet('Sheet1', function ($sheet) use ($reservations, $date) {
                    $sheet->loadView('admin.report.turbojet-virtual-report', compact('reservations', 'date'));
                });
            })->store('xlsx', storage_path('app/virtual-reports'));

            $report = new TurbojetVirtualReport();
            $report->{TurbojetVirtualReport::COLUMN_DATE} = $date->format("Y-m-d");
            $report->{TurbojetVirtualReport::COLUMN_NAME} = $filename . '.xlsx';
            $report->{TurbojetVirtualReport::COLUMN_PATH} = 'virtual-reports/' . $filename . '.xlsx';
            $report->{TurbojetVirtualReport::COLUMN_RECORD_COUNT} = sizeof($reservations);
            $report->save();


            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            throw $e;
        }

    }
}
