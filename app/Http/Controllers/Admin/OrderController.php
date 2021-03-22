<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;

class OrderController extends Controller
{
    protected $orderRepo;

    function __construct(Order $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        return view('admin.orders.index');
    }

    public function getorder()
    {
        $year = date('Y', strtotime('-1 year'));
        $orders = $this->orderRepo->rawWith(['customer.bill','item','shipment'], "year(created_at) >= ?", [$year], 'created_at','desc');
        return Datatables::of($orders)
        ->editColumn('order_number', function($orders){
            if(!empty($orders->item)){
                $quantity = 0;
                foreach ($orders->item as $item) {
                    $quantity += $item->quantity;
                }
                $html  = $orders->order_number.'<br>';
                $html .= '<small>Qty: '.$quantity.'</small>';
                return $html;
            }
            return '';
        })
        ->editColumn('buyer_name', function($orders) {
            if(!empty($orders->customer)){
                $html  = $orders->customer->fname.' '.$orders->customer->lname.'<br>';
                if(!empty($orders->customer->bill)){
                    $html .= '<small>'.$orders->customer->bill->street.' '.$orders->customer->bill->city.' '.$orders->customer->bill->state.'</small>';
                }
                return $html;
            }
            return '';
        })
        ->editColumn('tracking_number', function($orders) {
            if(!empty($orders->shipment)){
                $html  = $orders->shipment->tracking_number.'<br>';
                $html .= '<small>Carrier: '.$orders->shipment->carrier.' | <a href="'.$orders->shipment->label_url.'" target="_blank"><i class="fas fa-file-invoice fa-fw"></i> Receipt</a></small>';
                return $html;
            }
            return '';
        })
        ->editColumn('total_cost', function($orders) {
            return number_format($orders->total_cost,2);
        })
        ->editColumn('status', function($orders) {
            return $orders->status;
        })
        ->addColumn('action', function ($orders) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="updateorder(\''.$orders->id.'\')">Update Status</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        ->rawColumns(['order_number', 'buyer_name', 'tracking_number', 'action'])
        ->make(true);
    }
}
