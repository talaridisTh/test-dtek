<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function getNotifications()
    {
        $result = array();
        $date = date('Y-m-d');
        $result['future_quantities'] = DB::table('product_future_quantities')->where('arrival_date','like',$date)->get();
        $result['orders'] = DB::table('orders')->where('created_at','like',$date.'%')->where('order_status',1)->get();
        $result['low_Stock'] = DB::table('products')
                ->whereRaw('COALESCE((SELECT SUM(stock) FROM product_quantities WHERE product_id = products.id),0) < notify_quantity')
                ->get();

        $result['unpaid_orders'] = DB::select( DB::raw("SELECT * FROM `orders` o WHERE order_total>COALESCE((SELECT SUM(amount) FROM payments pm WHERE pm.order_id=o.order_id),0)") );
        $result['total'] = count($result['orders'])+count($result['future_quantities'])+count($result['low_Stock'])+count($result['unpaid_orders']);
        return $result;
    }
    static function getStats()
    {
        $result = array();
        $date = date('Y-m');
        $result['count_customers'] = DB::table('customers')->count();
        $result['count_orders'] = DB::table('orders')->where('order_status','>',5)->count();
        $result['count_done_orders'] = DB::table('orders')->where('order_status',5)->count();
        $result['total_orders'] = DB::table('orders')->where('order_status','>',5)->sum('order_total');

        $result['order_stats'] = DB::table('orders')->selectRaw('sum(order_total) as total,created_at')->where('order_status','>',5)->groupBy('created_at')
                ->get();
        $max = 0;

        foreach($result['order_stats'] as &$order_stats)
        {
            $order_stats->created_at = explode(" ",$order_stats->created_at)[0];
            if($max < $order_stats->total)
                $max = $order_stats->total;
        }

        $result['order_stats_step'] = (int)($max/10);

        $result['order_stats_max'] = $max;

        $result['order_stats_totals'] = DB::table('orders')->selectRaw('sum(order_total) as total')
        ->where('order_status','>',5)->where('created_at','like',$date.'%')->get()[0]->total;
        return $result;
    }
}
