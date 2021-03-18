<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;
use App\Repositories\Admin\OrderItemRepositoryEloquent as OrderItem;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;

class OrderController extends Controller
{
    protected $orderRepo;
    protected $orderItemRepo;
    protected $configRepo;
    protected $productRepo;

    function __construct(Order $orderRepo, 
                         OrderItem $orderItemRepo, 
                         Config $configRepo, 
                         Product $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->configRepo = $configRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $data['module'] = 'order';
        return view('admin.orders.index', $data);
    }

    public function getOrders (Request $request) 
    {
        $orders = $this->orderRepo->rawWith([
                                                            'customer',
                                                            'order_item', 
                                                            'order_item.customer',
                                                            'order_item.product', 
                                                            'order_item.product.brand', 
                                                            'order_item.product.photo', 
                                                            'order_item.network', 
                                                        ], 
                                                        "1 = ?", 
                                                        [1]);
                                                        
        return Datatables::of($orders)
        ->editColumn('seller_name', function($orders) {
            $html  = $orders['customer']['fullname'];
            return $html;
        })
        ->editColumn('order_no', function($orders) {
            $html  = $orders['order_no'];
            return $html;
        })
        ->editColumn('status', function($orders) {
            $html  = $orders['status']['name'];
            return $html;
        })
        ->editColumn('transaction_date', function($orders) {
            $html  = $orders['display_transaction_date'];
            return $html;
        })
        ->editColumn('delivery_due', function($orders) {
            $html  = $orders['display_delivery_due'];
            return $html;
        })
        ->addColumn('action', function ($orders) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/orders/'.$orders['hashedid']).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$orders['hashedid'].'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        // ->rawColumns(['photo', 'action', 'model','brand','amount'])
        ->rawColumns(['order_no', 'seller_name', 'status', 'transaction_date', 'delivery_due', 'action'])
        ->make(true);
    }


    public function edit ($hashedId) 
    {
        
        $data['module'] = 'order';
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['config'] = $this->configRepo->find(1);
        
        $data['order'] = $this->orderRepo
                                                ->rawByWithField(
                                                    [
                                                        'customer', 
                                                        'customer.bill',
                                                        'order_item',
                                                        'order_item.product',
                                                        'order_item.product.brand',
                                                        'order_item.network',
                                                        'order_item.product_storage'
                                                    ], "id = ?", [$id]);
        // $data['customer'] = $this->customerTransactionRepo->rawByWithField(['bill', 'sells', 'sells.product', 'sells.network', 'sells.product_storage'], "id = ?", [$id]);
        $data['hashedId'] = $hashedId;
        $data['products'] = $this->productRepo->rawWith(['brand','photo','networks.network','storages'], "status = ?", ['Active']);
        return view('admin.orders.edit', $data);
    }


    public function generatePDF ($hashedId) 
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $config = $this->configRepo->find(1);
        
        $shippingFee = 10;
        $overallSubTotal = 0;
        $counter = 1;

        $customer_transaction = $this->orderRepo->rawByWithField(
                                                    [
                                                        'customer', 
                                                        'customer.bill',
                                                        'order_item',
                                                        'order_item.product',
                                                        'order_item.product.brand',
                                                        'order_item.network',
                                                        'order_item.product_storage'
                                                    ], "id = ?", [$id]);

        $generateHtml = '<html>
                            <head>
                                <style>
                                    body, td {
                                        font-family: Arial, Helvetica, sans-serif;
                                        font-size: 12px;
                                    }
                                    .bordered {
                                        border: 1px solid #000;
                                    }
                                    .table:not(.table-dark) {
                                        color: inherit;
                                    }
                                    .table {
                                        width: 100%;
                                        margin-bottom: 1rem;
                                        color: #212529;
                                        background-color: transparent;
                                    }
                                    table {
                                        border-collapse: collapse;
                                    }
                                    table {
                                        // border-collapse: separate;
                                        text-indent: initial;
                                        border-spacing: 2px;
                                    }
                                    thead {
                                        display: table-header-group;
                                        vertical-align: middle;
                                        border-color: inherit;
                                    }
                                    .table th, .table td {
                                        padding: .75rem;
                                        vertical-align: top;
                                        border-top: 1px solid #dee2e6;
                                    }
                                    .valign-top {
                                        vertical-align: top;
                                    }
                                    .label {
                                        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                                        display: inline;
                                        padding: .2em .6em .3em;
                                        font-size: 75%;
                                        font-weight: 700;
                                        line-height: 1;
                                        color: #fff;
                                        text-align: center;
                                        white-space: nowrap;
                                        vertical-align: baseline;
                                        border-radius: .25em;
                                    }
                                    .label-default {
                                        background-color: #777;
                                    }
                                    .label-primary {
                                        background-color: #337ab7;
                                    }
                                    .label-success {
                                        background-color: #5cb85c;
                                    }
                                    .label-info {
                                        background-color: #5bc0de;
                                    }
                                    .label-warning {
                                        background-color: #f0ad4e;
                                    }
                                    .label-danger {
                                        background-color: #d9534f;
                                    }
                                    .pad10rem {
                                        padding: .10rem;
                                    }
                                    .lead {
                                        font-family: Arial, Helvetica, sans-serif;
                                        font-size: 1.25rem;
                                        font-weight: 300;
                                    }
                                    img { display:block }
                                </style>
                            </head>
                            <body>
                                <div class="borderedx">
                                    <table width="100%">
                                        <tr>
                                            <td><img src="http://tronicspay.saperemarketing.com/assets/images/logo.png"></td>
                                            <td align=right>Date: '.$customer_transaction['display_transaction_date'].'</td>
                                        </tr>
                                    </table>
                                    <br />
                                    <table width="100%">
                                        <tr>
                                            <td width="34%" style="vertical-align: top;">
                                                <div class="pad10rem">From</div>
                                                <div>
                                                    <div class="pad10rem"><strong>'.$customer_transaction['customer']['fullname'].'</strong><br></div>
                                                    <div class="pad10rem">'.$customer_transaction['customer']['bill']['address1'].'<br></div>
                                                    <div class="pad10rem">Phone: '.$customer_transaction['customer']['bill']['phone'].'<br></div>
                                                    <div class="pad10rem">Email: '.$customer_transaction['customer']['email'].'</div>
                                                </div>
                                            </td>
                                            <td width="33%" style="vertical-align: top;">
                                                <div class="pad10rem">To</div>
                                                <div>
                                                    <div class="pad10rem"><strong>'.$config['company_name'].'</strong><br></div>
                                                    <div class="pad10rem">'.$config['address1'].'<br></div>
                                                    <div class="pad10rem">Phone: '.$config['address1'].'<br></div>
                                                    <div class="pad10rem">Email: '.$config['company_email'].'</div>
                                                </div>
                                            </td>
                                            <td width="33%" style="vertical-align: top;">
                                                <div class="pad10rem"><b>Transaction #'.$customer_transaction['order_no'].'</b></div>
                                                <br />
                                                <div class="pad10rem"><b>Tracking Code:</b> '.$customer_transaction['tracking_code'].'<br /></div>
                                                <div class="pad10rem"><b>Status:</b> '.$customer_transaction['status'].'<br /></div>
                                                <div class="pad10rem"><b>Delivery Due:</b> '.$customer_transaction['display_delivery_due'].'<br /></div>
                                            </td>
                                        </tr>
                                    </table>
                                    <br />
                                    <table class="table table-striped" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="7%"><center>#</center></th>
                                                <th width="23%" style="text-align: left;">Product</th>
                                                <th width="14%"><center>Storage</center></th>
                                                <th width="14%"><center>Qty</center></th>
                                                <th width="14%"><center>Condition</center></th>
                                                <th width="14%"><center>Carrier</center></th>
                                                <th width="14%" style="text-align: right;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            foreach($customer_transaction['order_item'] as $key => $value)
                                            {
                                                $subTotal = $value['quantity'] * $value['amount'];
                                                $overallSubTotal = $overallSubTotal + $subTotal;
                                                $displayCounter = $counter + $key;
                                                $generateHtml .= '<tr>
                                                    <td align="center">'.$displayCounter.'</td>
                                                    <td>'.$value['product']['brand']['name'].' '.$value['product']['model'].'</td>
                                                    <td align="center">'.$value['product_storage']['title'].'</td>
                                                    <td align="center">'.$value['quantity'].'</td>
                                                    <td align="center">';
                                                        if ($value['device_type'] == 1) {
                                                            $generateHtml .= '<label class="label label-success">Excellent</label>';
                                                        } else if($value['device_type'] == 2) {
                                                            $generateHtml .= '<label class="label label-info">Good</label>';
                                                        } else if($value['device_type'] == 3) {
                                                            $generateHtml .= '<label class="label label-warning">Fair</label>';
                                                        } else {
                                                            $generateHtml .= '<label class="label label-danger">Poor</label>';
                                                        }
                                    $generateHtml .= '</td>
                                                    <td align="center">'.$value['network']['title'].'</td>
                                                    <td align="right">$'.number_format($subTotal, 2, '.', ',').'</td>
                                                </tr>';
                                            }
                        $generateHtml .= '</tbody>
                                        </table>
                                        <table width="100%">
                                            <tr>
                                                <td width="50%" class="valign-top">
                                                    <table width="100%">
                                                        <tr>
                                                            <td class="lead">Payment Method:</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pad10rem">'.$customer_transaction['payment_method'] .'</td>
                                                        </tr>';
                                                if ($customer_transaction['payment_method'] == "Bank Transfer") {
                                        $generateHtml .= '<tr>
                                                            <td class="pad10rem"><b>Bank Name: </b>'.$customer_transaction['account_bank'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pad10rem"><b>Account Name: </b>'.$customer_transaction['account_name'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pad10rem"><b>Account Number: </b>'.$customer_transaction['account_number'].'</td>
                                                        </tr>';
                                                } else {
                                        $generateHtml .= '<tr>
                                                            <td class="pad10rem"><b>Account Username: </b>'.$customer_transaction['account_username'].'</td>
                                                        </tr>';
                                                }
                        $generateHtml .= '</table>
                                        </td>
                                        <td width="50%" class="valign-top">
                                            <table class="table" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:50%">Subtotal:</th>
                                                        <td>$'.number_format($overallSubTotal, 2, '.', ',').'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Shipping:</th>
                                                        <td>$'.number_format($shippingFee, 2, '.', ',').'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td>$'.number_format($overallSubTotal + $shippingFee, 2, '.', ',').'</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                </div>';
            $generateHtml .= '</body>
                        </html>';

        // $pdf = \PDF::setOptions(['isRemoteEnabled' => true])->loadHTML($generateHtml);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setOptions(['isRemoteEnabled' => true])->loadHTML($generateHtml);
        return $pdf->download('Customer_Device_'.$customer_transaction['customer']['fullname'].'.pdf');
        return $pdf->stream();
        // $pdf = \PDF::loadView('home');
        // return $pdf->download('invoice.pdf');
        // $pdf = PDF::loadView('pdf.invoice', $data);
        // return $pdf->download('invoice.pdf');
        return $hashedId;
    }




    public function getOrderItem ($hashedId) 
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['customerSell'] = $this->orderItemRepo->rawByWithField(['product_storage'], 'id = ?', [$id]);
        $data['productDetails'] = $this->productRepo->rawByWithField(['networks.network'], 'id = ?', [$data['customerSell']['product_id']]);
        $data['productDetails']['storages'] = $data['productDetails']->storagesForBuying()->get();
        return $data;
    }


    // public function getorder()
    // {
    //     $year = date('Y', strtotime('-1 year'));
    //     $orders = $this->orderRepo->rawWith(['customer.bill','item','shipment'], "year(created_at) >= ?", [$year], 'created_at','desc');
    //     return Datatables::of($orders)
    //     ->editColumn('order_number', function($orders){
    //         if(!empty($orders->item)){
    //             $quantity = 0;
    //             foreach ($orders->item as $item) {
    //                 $quantity += $item->quantity;
    //             }
    //             $html  = $orders->order_number.'<br>';
    //             $html .= '<small>Qty: '.$quantity.'</small>';
    //             return $html;
    //         }
    //         return '';
    //     })
    //     ->editColumn('buyer_name', function($orders) {
    //         if(!empty($orders->customer)){
    //             $html  = $orders->customer->fname.' '.$orders->customer->lname.'<br>';
    //             if(!empty($orders->customer->bill)){
    //                 $html .= '<small>'.$orders->customer->bill->street.' '.$orders->customer->bill->city.' '.$orders->customer->bill->state.'</small>';
    //             }
    //             return $html;
    //         }
    //         return '';
    //     })
    //     ->editColumn('tracking_number', function($orders) {
    //         if(!empty($orders->shipment)){
    //             $html  = $orders->shipment->tracking_number.'<br>';
    //             $html .= '<small>Carrier: '.$orders->shipment->carrier.' | <a href="'.$orders->shipment->label_url.'" target="_blank"><i class="fas fa-file-invoice fa-fw"></i> Receipt</a></small>';
    //             return $html;
    //         }
    //         return '';
    //     })
    //     ->editColumn('total_cost', function($orders) {
    //         return number_format($orders->total_cost,2);
    //     })
    //     ->editColumn('status', function($orders) {
    //         return $orders->status;
    //     })
    //     ->addColumn('action', function ($orders) {
    //         $html_out  = '';
    //         $html_out .= '<div class="dropdown">';
    //             $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
    //             $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
    //                 $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="updateorder(\''.$orders->id.'\')">Update Status</a>';
    //             $html_out .= '</div>';
    //         $html_out .= '</div>';
    //         return $html_out;
    //     })
    //     ->rawColumns(['order_number', 'buyer_name', 'tracking_number', 'action'])
    //     ->make(true);
    // }
}
