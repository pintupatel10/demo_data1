<?php

namespace App\Http\Controllers\admin;

use App\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use PHPExcel_Style_Border;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Customer');
    }

    public function index(Request $request)
    {
        $filter = (object)[];

        $fields = [
            'start-date', 'end-date',
        ];

        foreach ($fields as $field)
        {
            $filter->{$field} = $request->input($field);
        }

        $query = OrderProduct::query();

        if ($request->has('start-date'))
            $query->where(OrderProduct::CREATED_AT, '>=', Carbon::parse($filter->{'start-date'}));

        if ($request->has('end-date'))
            $query->where(OrderProduct::CREATED_AT, '<', Carbon::parse($filter->{'end-date'})->addDay(1));

        $customers = $query->groupBy(OrderProduct::COLUMN_EMAIL)->get();


        $data['mainmenu'] = "Customer";
        $data['menu'] = "Customer";
        $data['customers'] = $customers;
        $data['filter'] = $filter;

        return view('admin.customers.index', $data);
    }

    public function show(Request $request, $encoded)
    {
        $email = base64_decode($encoded);

        $data['mainmenu'] = "Customer";
        $data['menu'] = "Customer";
        $data['order_products'] = OrderProduct::where(OrderProduct::COLUMN_EMAIL, $email)->orderBy('created_at', 'desc')->get();

        if (sizeof($data['order_products']) == 0)
            abort(404);

        return view('admin.customers.show', $data);
    }

    public function export(Request $request)
    {
        $query = OrderProduct::query();
        $filename = 'customers';

        if ($request->has('start-date')) {
            $query->where(OrderProduct::CREATED_AT, '>=', Carbon::parse($request->input('start-date')));
            $filename .= '-' . $request->input('start-date');
        }

        if ($request->has('end-date'))
        {
            $query->where(OrderProduct::CREATED_AT, '<', Carbon::parse($request->input('end-date'))->addDay(1));
            $filename .= '-' . $request->input('end-date');
        }

        $customers = $query->groupBy(OrderProduct::COLUMN_EMAIL)->get();

        Excel::create($filename, function ($excel) use ($customers) {
            $excel->sheet('Sheet1', function ($sheet) use ($customers) {
                $sheet->loadView('admin.customers.export', compact('customers'));

                $sheet->setWidth([
                    'A' => 8,
                    'B' => 13,
                    'C' => 13,
                    'D' => 15,
                    'E' => 40,
                ]);
            });
        })->download('xlsx');
    }
}
