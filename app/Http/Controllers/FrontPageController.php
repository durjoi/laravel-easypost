<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\PageRepositoryEloquent as Page;
use App\Repositories\Admin\SettingsBrandRepositoryEloquent as Brand;
use App\Repositories\Admin\PageRowRepositoryEloquent as PageRow;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Admin\PageColumnRepositoryEloquent as PageColumn;
use App\Repositories\Admin\PageStaticRepositoryEloquent as PageStatic;
use App\Repositories\Admin\PageContentRepositoryEloquent as PageContent;
use App\Repositories\Admin\PageSectionRepositoryEloquent as PageSection;
use App\Repositories\Admin\ProductPhotoRepositoryEloquent as ProductPhoto;
use App\Repositories\Customer\StateRepositoryEloquent as State;
use App\Repositories\Admin\PageBuilderRepositoryEloquent as PageBuilder;
use App\Repositories\Admin\NetworkRepositoryEloquent as NetworkRepo;
use Illuminate\Support\Facades\Auth;
use PDF;

use Illuminate\Routing\UrlGenerator;

class FrontPageController extends Controller
{
    protected $pageRepo;
    protected $sectionRepo;
    protected $rowRepo;
    protected $columnRepo;
    protected $contentRepo;
    protected $staticRepo;
    protected $productRepo;
    protected $productPhotoRepo;
    protected $brandRepo;
    protected $stateRepo;
    protected $pageBuilderRepo;
    protected $networkRepo;
    protected $url;


    function __construct(Page $pageRepo, 
                         PageSection $sectionRepo, 
                         PageRow $rowRepo, 
                         PageColumn $columnRepo, 
                         PageContent $contentRepo, 
                         PageStatic $staticRepo, 
                         Product $productRepo, 
                         ProductPhoto $productPhotoRepo, 
                         Brand $brandRepo, 
                         State $stateRepo, 
                         PageBuilder $pageBuilderRepo, 
                         NetworkRepo $networkRepo,
                         UrlGenerator $url)
    {
        $this->pageRepo = $pageRepo;
        $this->sectionRepo = $sectionRepo;
        $this->rowRepo = $rowRepo;
        $this->columnRepo = $columnRepo;
        $this->contentRepo = $contentRepo;
        $this->staticRepo = $staticRepo;
        $this->productRepo = $productRepo;
        $this->productPhotoRepo = $productPhotoRepo;
        $this->brandRepo = $brandRepo;
        $this->stateRepo = $stateRepo;
        $this->pageBuilderRepo = $pageBuilderRepo;
        $this->networkRepo = $networkRepo;
        $this->url = $url;
    }


    public function handleRequest ($uri) 
    {
        $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
        $data['page'] = $this->pageBuilderRepo->findByField('url', $uri);
        if ($data['page']) {
            $data['page_id'] = $data['page']->id;
            $data['reload_page_api'] = $this->url->to('/')."/builder/pagecontent/".$data['page_id']."";
        }
        $page_url = basename(request()->path());
        
        /**
         * start: Products Page
         */
        if ($page_url == "products") {
            return $this->products($data['page_id'] );
        }

        /**
         * start: Device Page
         */
        if ($page_url == "device") {
            if(session()->has('result')){
                return view('front.device.index');
            }
            return redirect()->to('/');
        }

        /**
         * start: Customer Cart Page
         */
        if ($page_url == "cart") {
            $data['brands'] = $this->brandRepo->all();
            $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;

            $data['meta'] = ['<meta property="og:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                    '<meta property="og:url" content="'.url('/cart').'" />', 
                    '<meta name="twitter:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                    '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />'
            ];
            return view("front.cart.index", $data);
        }

        /**
         *  start: About Us
         */
        if ($page_url == "about-us") {
            $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;

            $data['meta'] = ['<meta property="og:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                    '<meta property="og:url" content="'.url('/about-us').'" />', 
                    '<meta name="twitter:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                    '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />'
            ];
            return view('front.aboutus.index', $data);
        }
        
        return view('front.pagebuilder.pagehandler', $data);
    }


    public function processRequest ($id) 
    {
        return $data['page'] = $this->pageBuilderRepo->find($id);
        return $id;
    }


    public function landingPage()
    {
        $data['page'] = $this->pageBuilderRepo->findByField('url', '/');
        // $data['page'] = $this->pageBuilderRepo->find(1);
        $data['page_id'] = $data['page']->id;
        $data['reload_page_api'] = $this->url->to('/')."/builder/pagecontent/".$data['page_id']."";
        $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;

        $data['meta'] = ['<meta property="og:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                '<meta property="og:url" content="'.url('/').'" />', 
                '<meta name="twitter:title" content="Sell used Cell Phones, Game Consoles and Electronics. Get Paid!" />', 
                '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />'
        ];
        return view('welcome', $data);
    }

    // public function aboutus()
    // {

    //     $page_url = basename(request()->path());


    //     $data['page'] = $this->pageBuilderRepo->findByField('url', $page_url);
    //     $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
    //     return view('front.pagebuilder.aboutus', $data);
    //     // $data['page'] = $this->staticRepo->findByField('page_id', 2);
    //     // return view('front.aboutus', $data);
    // }

    // public function loadAboutUs($id)
    // {
    //     $data['page'] = $this->pageBuilderRepo->findByField('url', $page_url);
    //     return view('front.pagebuilder.aboutus', $data);
    // }

    public function howitworks()
    {
        return view('front.howitworks');
    }

    public function contactus()
    {
        $data['page'] = $this->staticRepo->findByField('page_id', 4);
        return view('front.contactus', $data);
    }

    public function custompage($page_slug)
    {
        $data['page'] = $page = $this->pageRepo->findByField('slug_title', $page_slug);
        $data['sections'] = $this->sectionRepo->rawWith(['row.column.content' => function($query){
            $query->orderBy('order_id');
        }], "page_id = ?", [$page->id], "order_id");
        $data['customstyle'] = ($page->background_image) ? 'background-image: url('.$page->background_image.')' : 'background-color: '.$page->background_color;
        return view('front.custompage', $data);
    }

    public function products($page_id)
    {
        $data['page'] = $this->pageBuilderRepo->find($page_id);
        $data['page_id'] = $data['page']->id;
        $data['reload_page_api'] = $this->url->to('/')."/builder/pagecontent/".$data['page_id']."";
        return view('front.products', $data);
    }

    public function getProductList () 
    {
        $products = $this->productRepo->rawWith(['photo' => function($query){
            $query->first();
        }], "status = ?", ['Active']);
        $output['response'] = ($products != null) ? 200 : 204;
        $output['message'] = ($products != null) ? "Products retrived" : "No Products Found";
        $output['products'] = $products;
        return $output;
    }

    /**
     * Old Product Method
     */

    // public function products()
    // {
    //     $data['products'] = $this->productRepo->rawWith(['photo' => function($query){
    //         $query->first();
    //     }], "status = ?", ['Active']);
    //     return view('front.products', $data);
    // }

    public function productdetails($model)
    {
        $id = substr($model, strrpos($model, '-') + 1);
        $data['product'] = $this->productRepo->findWith($id, ['brand']);
        $data['photos'] = $this->productPhotoRepo->rawAll("product_id = ?", [$id]);
        return view('front.product-details', $data);
    }

    public function productsearch(Request $request)
    {
        $searchname = $request['productname'];
        if($searchname == ''){
            $data['products'] = $this->productRepo->rawWith(['photo'], "status = ? and device_type in ('Sell','Both')", ['Active']);
            return view('front.products', $data);
        }
    }

    public function productsell()
    {
        $data['products'] = $this->productRepo->rawWith(['photo'], "status = ? and device_type in ('Buy','Both')", ['Active']);
        return view('front.productsell', $data);
    }

    public function productselldetails($id)
    {
        $data['product'] = $this->productRepo->findWith($id, ['photo']);
        $data['stateList'] = $this->stateRepo->selectlist('name', 'abbr');
        $data['paymentList'] = [
            '' => '--',
            'Apple Pay' => 'Apple Pay',
            'Google Pay' => 'Google Pay',
            'Venmo' => 'Venmo',
            'Cash App' => 'Cash App',
            'Paypal' => 'Paypal',
            'Bank Transfer' => 'Bank Transfer'
        ];
        return view('front.product-sell-details', $data);
    }

    public function paymentmethod(Request $request)
    {
        $method = $request['payment'];
        $content = '';
        if($method == 'Apple Pay'){
            $content .= '<div class="form-group col-md-5">';
                $content .= '<label class="col-form-label col-form-label-sm">Apple ID</label>';
                $content .= '<input type="text" name="account_username" class="form-control form-control-sm">';
            $content .= '</div>';
        }
        
        if($method == 'Google Pay'){
            $content .= '<div class="form-group col-md-5">';
                $content .= '<label class="col-form-label col-form-label-sm">Google Email or Mobile Number</label>';
                $content .= '<input type="text" name="account_username" class="form-control form-control-sm">';
            $content .= '</div>';
        }

        if($method == 'Venmo'){
            $content .= '<div class="form-group col-md-5">';
                $content .= '<label class="col-form-label col-form-label-sm">Venmo Email or Mobile Number</label>';
                $content .= '<input type="text" name="account_username" class="form-control form-control-sm">';
            $content .= '</div>';
        }

        if($method == 'Cash App'){
            $content .= '<div class="form-group col-md-5">';
                $content .= '<label class="col-form-label col-form-label-sm">Cash App Email or Mobile Number</label>';
                $content .= '<input type="text" name="account_username" class="form-control form-control-sm">';
            $content .= '</div>';
        }

        if($method == 'Paypal'){
            $content .= '<div class="form-group col-md-5">';
                $content .= '<label class="col-form-label col-form-label-sm">Paypal Email Address</label>';
                $content .= '<input type="text" name="account_username" class="form-control form-control-sm">';
            $content .= '</div>';
        }
        
        if($method == 'Bank Transfer'){
            $content .= '<div class="form-group col-md-4">';
                $content .= '<label class="col-form-label col-form-label-sm">Bank</label>';
                $content .= '<input type="text" name="bank" class="form-control form-control-sm">';
            $content .= '</div>';
            $content .= '<div class="form-group col-md-4">';
                $content .= '<label class="col-form-label col-form-label-sm">Account Name</label>';
                $content .= '<input type="text" name="account_name" class="form-control form-control-sm">';
            $content .= '</div>';
            $content .= '<div class="form-group col-md-4">';
                $content .= '<label class="col-form-label col-form-label-sm">Accoung Number</label>';
                $content .= '<input type="text" name="account_number" class="form-control form-control-sm">';
            $content .= '</div>';
        }

        return response()->json(['content'=>$content]);
    }

    public function getCartList (Request $request) 
    {
        if (!$request->sessionCart) {
            $data['hasCart'] = false;
            // return $data['cartHtml'] = base_path()."/public/assets/images/empty-cart.png";
            $data['cartHtml'] = '<div class="form-group"><img src="/assets/images/empty-cart.png" class="img-fluid"></div>';
        } else {
            $data['hasCart'] = true;
            $data['cartHtml'] = '<div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mt-10">Your Items</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%"></th>
                                                            <th width="13%"></th>
                                                            <th width="34%">Item</th>
                                                            <th width="16%">Cash Offer</th>
                                                            <th width="16%">Quantity</th>
                                                            <th width="16%">SubTotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                $subTotal = 0;
                foreach ($request->sessionCart as $key => $value) 
                {
                    $device_type = '';
                    if ($value['device_type'] == 1) {
                        $device_type = 'Excellent';
                    } else if ($value['device_type'] == 2) {
                        $device_type = 'Good';
                    } else if ($value['device_type'] == 3) {
                        $device_type = 'Fair';
                    } else if ($value['device_type'] == 4) {
                        $device_type = 'Broken';
                    }
                    
                    $network = $this->networkRepo->find($value['cart_id']);
                    
                    $brands = $this->brandRepo->findByField('name', $value['brand']);
                    $product = $this->productRepo->rawWith(['photo'], "brand_id = ? and model = '".$value["model"]."'", [$brands['id']])->first();
                    
                    $itemSubTotal = $value['amount'] * $value['quantity'];
                    $subTotal = $subTotal + $itemSubTotal;
                    $data['cartHtml'] .= '<tr>
                                            <td align="center" class="valign-middle">
                                                <a href="javascript:void(0)" class="removeItem" data-attr-id="'.$key.'">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                            <td align="center" class="valign-middle">
                                                <img src="'.$product['photo']['photo'].'" class="img-fluid">
                                            </td>
                                            <td align="left" class="valign-middle font14">
                                                <b>Model:</b> '.$value['brand'].' '.$value['model'].'<br /> 
                                                <b>Storage:</b> '.$value['storage'].'<br />
                                                <b>Carrier:</b> '.$network['title'].'<br />
                                                <b>Condition: </b> '.$device_type.'
                                            </td>
                                            <td align="right" class="valign-middle font14">
                                                $'.number_format($value['amount'], 2).'
                                            </td>
                                            <td align="center" class="valign-middle">
                                                <input type="number" name="quantity[]" min="1" data-attr-id="'.$key.'" class="form-control cart-item-quantity"  style="width: 75px !important;" value="'.$value['quantity'].'">
                                            </td>
                                            <td align="right" class="valign-middle font14">
                                                $'.number_format($itemSubTotal, 2).'
                                            </td>
                                </tr>';
                }

                                $data['cartHtml'] .= '</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>';
                $data['subTotal'] = '$'.number_format($subTotal, 2);
        }
        return $data;
    }
}
