<?php

namespace App\Http\Controllers\admin;

use App\Article;
use App\Order;
use App\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       // $this->middleware('spylogs');
       // $this->middleware('LogActivity',['except'=>['index','show']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        $data['mainmenu'] = "Dashboard";
        $data['menu'] = "Dashboard";

        if(Auth::User()->role != 'Staff') {
            $data['latestActivities'] = Activity::with('user')->latest()->limit(100)->get();
        }
        else{
            if(Auth::User()->actionlog_show == 'Yes'){
                $data['latestActivities'] = Activity::with('user')->latest()->limit(100)->get();
            }
            else{
                $data['latestActivities'] = Activity::with('user')->where('user_id', Auth::User()->id)->latest()->get();
            }

           // $data['latestActivities'] = Activity::with('user')->where('user_id','!=',Auth::User()->id)->get();
        }

        $yesterday = Carbon::today()->addDay(-1);
        $today = Carbon::today();

        $data['yesterday_count'] = OrderProduct::where(OrderProduct::CREATED_AT, '>=', $yesterday)
            ->where(OrderProduct::CREATED_AT, '<', $today)
            ->count();
        $data['yesterday_url'] = url('admin/order?start-date=' . $yesterday->format('Y-m-d') . '&end-date=' . $yesterday->format('Y-m-d'));

        $data['today_count'] = OrderProduct::where(OrderProduct::CREATED_AT, '>=', $today)->count();
        $data['today_url'] = url('admin/order?start-date=' . $today->format('Y-m-d'));

        $data['incomplete_count'] = OrderProduct::whereIn(OrderProduct::COLUMN_STATUS, [
            OrderProduct::STATUS_IN_QUEUE,
            OrderProduct::STATUS_PENDING,
            OrderProduct::STATUS_CONFIRM_DRAFTED,
            OrderProduct::STATUS_CONFIRMED,
        ])->count();
        $data['incomplete_url'] = url('admin/order?status%5B%5D=' . OrderProduct::STATUS_IN_QUEUE . '&status%5B%5D=' . OrderProduct::STATUS_PENDING
            . '&status%5B%5D=' . OrderProduct::STATUS_CONFIRM_DRAFTED . '&status%5B%5D=' . OrderProduct::STATUS_CONFIRMED);

        $data['yesterday_amount'] = Order::where(Order::CREATED_AT, '>=', $yesterday)
            ->where(Order::CREATED_AT, '<', $today)
            ->sum(Order::COLUMN_TOTAL_AMOUNT);

        // Chart Data
        $chart_labels = [];
        $chart_data = [];
        $from = Carbon::today()->addDay(-30);
        $orders = Order::where(Order::CREATED_AT, '>=', $from)->get();

        while ($from <= $today)
        {
            $key = $from->format("j/n");
            $chart_labels[] = $key;
            $chart_data[$key] = 0;
            $from->addDay(1);
        }

        foreach ($orders as $order)
        {
            $chart_data[$order->created_at->format("j/n")] += $order->{Order::COLUMN_TOTAL_AMOUNT};
        }

        $data['amount_chart_labels'] = $chart_labels;
        $data['amount_chart_data'] = array_values($chart_data);

        return view("admin.dashboard",$data);
    }
}
