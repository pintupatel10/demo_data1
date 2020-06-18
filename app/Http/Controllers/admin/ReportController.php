<?php

namespace App\Http\Controllers\admin;

use App\OrderProduct;
use App\Transactionorder;
use App\Transactionproduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;


class ReportController extends Controller
{
    const REPORT_BANK = 'bank';
    const REPORT_BOOKING_REMINDER = 'reminder';
    const REPORT_DAILY_SALES_1 = 'daily-1';
    const REPORT_DAILY_SALES_2 = 'daily-2';
    const REPORT_RESERVATION = 'reservation';
    const REPORT_DAILY_TRANSACTION = 'daily-trans';

    public static $reports = [
        self::REPORT_BANK => 'Bank Record',
        self::REPORT_BOOKING_REMINDER => 'Tour Booking Reminder',
        self::REPORT_RESERVATION => 'Reservation Report',
        self::REPORT_DAILY_TRANSACTION => 'Daily Transaction',
        self::REPORT_DAILY_SALES_1 => 'Daily Sales Report 1',
        self::REPORT_DAILY_SALES_2 => 'Daily Sales Report 2',
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Report');
    }

    public function index()
    {
        $data['mainmenu'] = "Report";
        $data['menu'] = "Report";
        $data['reports'] = self::$reports;
        return view('admin.report.index', $data);
    }

    public function export(Request $request)
    {
        $type = $request->input('type');

        $panel = "single";
        if ($type == self::REPORT_BANK)
            $panel = "multiple";
        else if ($type == self::REPORT_BOOKING_REMINDER)
            $panel = "none";
        else if ($type == self::REPORT_RESERVATION)
            $panel = "multiple";

        if ($panel == "single") {
            $this->validate($request, [
                'date' => 'required|date'
            ]);
        } else if ($panel == "multiple") {
            $this->validate($request, [
                'from' => 'required|date',
                'to' => 'required|date'
            ]);
        }


        if ($type == self::REPORT_BANK) {
            $from = Carbon::parse($request->input('from'))->addHour(-1);        // DBS Transaction cutoff time is 2300
            $to = Carbon::parse($request->input('to'))->addHour(23);
            $filename = 'bank-record-' . $request->input('from') . '-' . $request->input('to');

            $order_products = OrderProduct::where(OrderProduct::CREATED_AT, '>=', $from)
                ->where(OrderProduct::CREATED_AT, '<', $to)
                ->orderBy(OrderProduct::CREATED_AT, 'asc')
                ->get();

            self::export_bank_record($filename, $order_products);
        }
        else if ($type == self::REPORT_BOOKING_REMINDER)
        {
            $order_products = OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, [
                OrderProduct::STATUS_IN_QUEUE,
                OrderProduct::STATUS_PENDING,
                OrderProduct::STATUS_CONFIRM_DRAFTED,
                OrderProduct::STATUS_CONFIRMED,
            ])->orderBy(OrderProduct::COLUMN_DATE, 'asc')
                ->get();

            $title = 'Tour Booking Reminder  (To do list- carry forward)';
            $filename = 'reminder';

            self::export_sales_report($filename, $order_products, $title);
        }
        else if ($type == self::REPORT_RESERVATION)
        {
            $from = Carbon::parse($request->input('from'));
            $to = Carbon::parse($request->input('to'))->addDay(1);

            $order_products = OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, [
                OrderProduct::STATUS_COMPLETED,
                OrderProduct::STATUS_CANCELED,
            ])->where(OrderProduct::COLUMN_COMPLETED_AT, '>=', $from)
                ->where(OrderProduct::COLUMN_COMPLETED_AT, '<', $to)
                ->orderBy(OrderProduct::CREATED_AT, 'asc')
                ->get();

            $title = 'Reservation Report - Prior Bookings (if completed)';
            $filename = 'reservation-' . $request->input('from') . '-' . $request->input('to');

            self::export_sales_report($filename, $order_products, $title);
        }
        else if ($type == self::REPORT_DAILY_TRANSACTION)
        {
            $from = Carbon::parse($request->input('date'));
            $to = $from->copy()->addDay(1);

            $order_products = OrderProduct::where(OrderProduct::CREATED_AT, '>=', $from)
                ->where(OrderProduct::CREATED_AT, '<', $to)
                ->orderBy(OrderProduct::CREATED_AT, 'asc')
                ->get();

            $title = 'Daily Transaction';
            $filename = 'daily-transaction-' . $request->input('date');

            self::export_sales_report($filename, $order_products, $title);
        }
        else if ($type == self::REPORT_DAILY_SALES_1)
        {
            $from = Carbon::parse($request->input('date'));
            $to = $from->copy()->addDay(1);

            $order_products = OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, [
                OrderProduct::STATUS_COMPLETED,
                OrderProduct::STATUS_CANCELED,
            ])->where(OrderProduct::CREATED_AT, '>=', $from)
                ->where(OrderProduct::CREATED_AT, '<', $to)
                ->orderBy(OrderProduct::CREATED_AT, 'asc')
                ->get();

            $title = 'Daily Sales Report 1 (already input to Tour Booking System)';
            $filename = 'daily-sales-1-' . $request->input('date');

            self::export_sales_report($filename, $order_products, $title);
        }
        else if ($type == self::REPORT_DAILY_SALES_2)
        {
            $from = Carbon::parse($request->input('date'));
            $to = $from->copy()->addDay(1);

            $order_products = OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, [
                OrderProduct::STATUS_IN_QUEUE,
                OrderProduct::STATUS_PENDING,
                OrderProduct::STATUS_CONFIRM_DRAFTED,
                OrderProduct::STATUS_CONFIRMED,
            ])->where(OrderProduct::CREATED_AT, '>=', $from)
                ->where(OrderProduct::CREATED_AT, '<', $to)
                ->orderBy(OrderProduct::CREATED_AT, 'asc')
                ->get();

            $title = 'On day Daily Sales Report 2  (to be input to Tour Booking System)';
            $filename = 'daily-sales-2-' . $request->input('date');

            self::export_sales_report($filename, $order_products, $title);
        }
    }

    private function export_bank_record($filename, $order_products)
    {
        Excel::create($filename, function ($excel) use ($order_products) {

            $excel->sheet('Sheet1', function ($sheet) use ($order_products) {

                $sheet->loadView('admin.report.bank-report', compact('order_products'));

                $sheet->setFontFamily('Arial');
                $sheet->setFontSize(12);
                $sheet->setWidth([
                    'A' => 20,
                    'B' => 20,
                    'C' => 14,
                    'D' => 14,
                    'E' => 50,
                    'F' => 6,
                    'G' => 6,
                    'H' => 6,
                    'I' => 6,
                    'J' => 6,
                    'K' => 6,
                    'L' => 10,
                    'M' => 10,
                    'N' => 10,
                    'O' => 35,
                    'P' => 12,
                    'Q' => 12,
                    'R' => 17,
                ]);

                $last_row = 4 + $order_products->count();

                // Merge Cells
                $header_rowspans = ['A', 'B', 'C', 'D', 'E', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
                foreach ($header_rowspans as $col)
                {
                    $sheet->mergeCells($col . "3:$col" . "4");
                }
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("F3:K3");
                $sheet->mergeCells("L" . ($last_row+1) . ":M" . ($last_row+1));

                // borders
                $sheet->getStyle("A3:R$last_row")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'inside' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // adult double border
                $sheet->getStyle("F4:G$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // child double border
                $sheet->getStyle("H4:I$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // senior double border
                $sheet->getStyle("J4:K$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // Alignments
                $sheet->getStyle("A1:R$last_row")->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->cells("E5:E$last_row", function ($cells){
                    $cells->setAlignment('left');
                });

                // Title
                $sheet->cells("A1:R1", function ($cells){
                    $cells->setFontSize(22);
                });

                // Column Format
                $sheet->setColumnFormat([
                    "O5:O$last_row" => '0',
                    "N5:N" . ($last_row + 1) => '0.00',
                ]);

            });

        })->download('xlsx');
    }

    private function export_sales_report($filename, $order_products, $title)
    {
        Excel::create($filename, function ($excel) use ($order_products, $title) {

            $excel->sheet('Sheet1', function ($sheet) use ($order_products, $title) {

                $sheet->loadView('admin.report.sales-report', compact('order_products', 'title'));

                $sheet->setFontFamily('Arial');
                $sheet->setFontSize(12);
                $sheet->setWidth([
                    'A' => 12,
                    'B' => 20,
                    'C' => 35,
                    'D' => 12,
                    'E' => 16,
                    'F' => 12,
                    'G' => 50,
                    'H' => 6,
                    'I' => 6,
                    'J' => 6,
                    'K' => 6,
                    'L' => 6,
                    'M' => 6,
                    'N' => 10,
                    'O' => 14,
                    'P' => 21,
                    'Q' => 14,
                    'R' => 14,
                    'S' => 12,
                    'T' => 12,
                    'U' => 12,
                ]);

                $last_row = 4 + $order_products->count();

                // Merge Cells
                $header_rowspans = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
                foreach ($header_rowspans as $col)
                {
                    $sheet->mergeCells($col . "3:$col" . "4");
                }
                $sheet->mergeCells("A1:U1");
                $sheet->mergeCells("H3:M3");
                $sheet->mergeCells("L" . ($last_row+1) . ":M" . ($last_row+1));

                // borders
                $sheet->getStyle("A3:U$last_row")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'inside' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // adult double border
                $sheet->getStyle("H4:I$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // child double border
                $sheet->getStyle("J4:K$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // Senior double border
                $sheet->getStyle("L4:M$last_row")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'style' => 	PHPExcel_Style_Border::BORDER_DOUBLE,
                            'color' => ['argb' => 'FF000000'],
                        ]
                    ]
                ]);

                // Alignments
                $sheet->getStyle("A1:U$last_row")->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'wrap' => true,
                    ],
                ]);

                $sheet->cells("G5:G$last_row", function ($cells){
                    $cells->setAlignment('left');
                });

                // Title
                $sheet->cells("A1:U1", function ($cells){
                    $cells->setFontSize(22);
                });

                // Column Format
                $sheet->setColumnFormat([
                    "C5:C$last_row" => '0',
                    "N5:N" . ($last_row + 1) => '0.00',
                ]);

                // Tick and Cross for completed column
                $sheet->cells("S5:S$last_row", function ($cells){
                    $cells->setFontFamily('Wingdings 2');
                });

            });

        })->download('xlsx');
    }
}
