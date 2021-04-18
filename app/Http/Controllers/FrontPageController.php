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
use App\Repositories\Admin\NetworkRepositoryEloquent as Network;
use App\Repositories\Admin\PageMetaTagRepositoryEloquent as PageMetaTag;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\TableList as Tablelist;


// For Page Builder
use PHPageBuilder\PHPageBuilder;
use PHPageBuilder\Theme;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Repositories\PageTranslationRepository;

use App\Models\Admin\PageBuilderPages;
use App\Models\Admin\PageBuilderPageTranslations;



// For Twilio
require __DIR__ . '/../../../vendor/twilio/sdk/src/Twilio/autoload.php';
use Twilio\Rest\Client;

// For Plivio
// require __DIR__ . '/../../../vendor/autoload.php';
// use Plivo\RestClient;

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
    protected $tablelist;
    protected $pageMetaTagRepo;
    protected $pageBuiderPages;
    protected $pageBuilderPageTranslations;

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
                         Network $networkRepo,
                         UrlGenerator $url, 
                         TableList $tablelist, 
                         PageMetaTag $pageMetaTagRepo, 
                         PageBuilderPages $pageBuiderPages, 
                         PageBuilderPageTranslations $pageBuilderPageTranslations)
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
        $this->tablelist = $tablelist;
        $this->pageMetaTagRepo = $pageMetaTagRepo;
        $this->pageBuiderPages = $pageBuiderPages;
        $this->pageBuilderPageTranslations = $pageBuilderPageTranslations;
    }



    public function landingPage()
    {
        $currentUrl = phpb_current_relative_url();
        $pagetranslation = $this->pageBuilderPageTranslations->where('route', $currentUrl)->first();
        $page = $this->pageBuiderPages->find($pagetranslation->page_id);
        $default_empty_content = '{"html":[""],"components":[[]],"css":"* { box-sizing: border-box; } body {margin: 0;}","style":[],"blocks":{"en":[]}}';
        if ($page->data == null || $page->data == $default_empty_content) {
            $data['page'] = $page;
            $data['page_id'] = $data['page']->id;
            $data['rowone'] = $this->brandRepo->rawAll("feature = ?", [1]);
            $data['rowtwo'] = $this->brandRepo->rawAll("feature = ?", [2]);
            $data['rowtri'] = $this->brandRepo->rawAll("feature = ?", [3]);
            $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
            $data['meta'] = $this->GenerateMetaTags('/');
            return view('welcome', $data);
        } else {
            $urlTitle = (substr($currentUrl, 1) == '') ? '/' : substr($currentUrl, 1);
            $parameters = array();
            array_push($parameters, $urlTitle);
            
            $page = (new PageTranslationRepository)->findWhere("route", $urlTitle);
            if ($page == null) {
                return view('404');
                return "invalid page";
            }

            $data['meta'] = $this->GenerateMetaTags($currentUrl);
            $data['html'] = $this->trimPageContent($page);
            return view('layouts.pagebuilder', $data);
        }
    }

    private function displayPageBuildTemplate ($currentUrl) 
    {

    }


    public function handleRequest ($uri) 
    {
        $currentUrl = basename(request()->path());
        
        $pagetranslate = $this->pageBuilderPageTranslations->firstWhere('route', $currentUrl);
        $default_empty_content = '{"html":[""],"components":[[]],"css":"* { box-sizing: border-box; } body {margin: 0;}","style":[],"blocks":{"en":[]}}';
  
        if (strlen($pagetranslate) != 0) {
            
                $page = $this->pageBuiderPages->find($pagetranslate->page_id);
                if ($page->data == null || $page->data == $default_empty_content) {
                    $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
                    if ($currentUrl == "about-us") {
                        $data['meta'] = $this->GenerateMetaTags('about-us');
                        return view('front.aboutus.index', $data);
                    }
                    
                    if ($currentUrl == "how-it-works") {
                        $data['meta'] = $this->GenerateMetaTags('how-it-works');
                        return view('front.howitworks.index', $data);
                    }
    
                    if ($currentUrl == "cart") {
                        $data['brands'] = $this->brandRepo->all();
            
                        $data['meta'] = [
                                '<meta name="title" content="Cart - Tronics Pay" />', 
                                '<meta name="description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />', 
                                '<meta property="og:type" content="article" />', 
                                '<meta property="og:title" content="Cart - Tronics Pay" />',
                                '<meta property="og:url" content="'.url('/cart').'" />', 
                                '<meta property="og:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />',
                                '<meta name="twitter:title" content="Cart - Tronics Pay" />', 
                                '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />', 
                                '<meta name="twitter:url" content="'.url('/cart').'" />',
                                '<meta name="twitter:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />'
                        ];
                        return view("front.cart.index", $data);
                    }
                    
                    if ($currentUrl == "contact-us") {
                        $data['page'] = $this->staticRepo->findByField('page_id', 4);
                        $data['meta'] = $this->GenerateMetaTags('contact-us');
                        return view('front.contactus.index', $data);
                    }

                } else {
                    $urlTitle = $currentUrl;
                    $parameters = array();
                    array_push($parameters, $urlTitle);
                    
                    $page = (new PageTranslationRepository)->findWhere("route", $urlTitle);
                    if ($page == null) {
                        return view('404');
                        return "invalid page";
                    }
        
                    $data['meta'] = $this->GenerateMetaTags($currentUrl);
                    $data['html'] = $this->trimPageContent($page);
                    return view('layouts.pagebuilder', $data);
                    echo '<pre>';
                    print_r($currentUrl);
                    echo '</pre>';
                    exit;
                }


            // $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
            // if ($currentUrl == "about-us") {
            //     $data['meta'] = $this->GenerateMetaTags('about-us');
            //     return view('front.aboutus.index', $data);
            // }
            
            // if ($currentUrl == "how-it-works") {
            //     $data['meta'] = $this->GenerateMetaTags('how-it-works');
            //     return view('front.howitworks.index', $data);
            // }

            // if ($currentUrl == "cart") {
            //     $data['brands'] = $this->brandRepo->all();
    
            //     $data['meta'] = [
            //             '<meta name="title" content="Cart - Tronics Pay" />', 
            //             '<meta name="description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />', 
            //             '<meta property="og:type" content="article" />', 
            //             '<meta property="og:title" content="Cart - Tronics Pay" />',
            //             '<meta property="og:url" content="'.url('/cart').'" />', 
            //             '<meta property="og:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />',
            //             '<meta name="twitter:title" content="Cart - Tronics Pay" />', 
            //             '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />', 
            //             '<meta name="twitter:url" content="'.url('/cart').'" />',
            //             '<meta name="twitter:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />'
            //     ];
            //     return view("front.cart.index", $data);
            // }

        } else {
            echo '<pre>';
            print_r($currentUrl);
            echo '</pre>';
            exit;
        }




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

            $data['meta'] = [
                    '<meta name="title" content="Cart - Tronics Pay" />', 
                    '<meta name="description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />', 
                    '<meta property="og:type" content="article" />', 
                    '<meta property="og:title" content="Cart - Tronics Pay" />',
                    '<meta property="og:url" content="'.url('/cart').'" />', 
                    '<meta property="og:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />',
                    '<meta name="twitter:title" content="Cart - Tronics Pay" />', 
                    '<meta name="twitter:image" content="'.url('/assets/images/logo-white.png').'" />', 
                    '<meta name="twitter:url" content="'.url('/cart').'" />',
                    '<meta name="twitter:description" content="Sell your used cell phones and electronics. Sell your iPhone, Samsung Galaxy, iPad, Smart Watches, Game Consoles and more for cash. We will pay you!" />'
            ];
            
            
            return view("front.cart.index", $data);
        }

        /**
         *  start: About Us
         */
        if ($page_url == "about-us") {
            $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
            $data['meta'] = $this->GenerateMetaTags('about-us');
            return view('front.aboutus.index', $data);
        }

        /**
         *  start: About Us
         */
        if ($page_url == "how-it-works") {
            $data['isValidAuthentication'] = (Auth::guard('customer')->check() != null) ? true : false;
            $data['meta'] = $this->GenerateMetaTags('how-it-works');
            return view('front.howitworks.index', $data);
        }

        return view('front.pagebuilder.pagehandler', $data);
    }


    public function processRequest ($id) 
    {
        return $data['page'] = $this->pageBuilderRepo->find($id);
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
        $data['meta'] = $this->GenerateMetaTags('contact-us');
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
        $data['paymentList'] = $this->tablelist->payment_list;
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


    private function GenerateMetaTags ($page_url) 
    {
        $pagetranslation = $this->pageBuilderPageTranslations->where('route', $page_url)->first();
        $page = $this->pageBuiderPages->find($pagetranslation->page_id);

        $meta = [];
        $pageMeta = $this->pageMetaTagRepo->rawByFieldAll("page_id = ?", [$page->id]);
        foreach ($pageMeta as $key => $val) {
            $meta[] = '<meta '.$val['meta_type'].'="'.$val['name'].'" content="'.$val['content'].'" />';
        }
        return $meta;
    }


    private function trimPageContent ($page) 
    {
        // return phpb_e(phpb_full_url(phpb_current_relative_url()));
        $pageId = $page[0]->page_id;
        
        $theme = new Theme(config('pagebuilder.theme'), config('pagebuilder.theme.active_theme'));
        $page = (new PageRepository)->findWithId($pageId);
        $pageRenderer = new PageRenderer($theme, $page);

        $openingHtmlTag = '<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->


    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="shortcut icon" href="'.url('/library/images/favicon.ico').'" />
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="'.url('/library/images/favicon.ico').'" sizes="32x32" type="image/png">
    <link rel="icon" href="'.url('/library/images/favicon.ico').'" sizes="16x16" type="image/png">
    <link rel="manifest" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="/themes/demo/css/style.css" />
</head>
<body>
';
        $closingHtmlTag = '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<script src="https://getbootstrap.com/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>';

        $html = str_replace($openingHtmlTag, '', $pageRenderer->render());
        $html = str_replace($closingHtmlTag, '', $html);
        return $html;
    }

    public function test () 
    {
        

        /**
         * Plivo
         */
        // $client = new RestClient("MAMTDJN2Q2Y2Q3NJY5MJ", "ZGM5YzUzNTZlODJmNjkyNDIxNDRjYjQ1NDAwMjhk");
        // $message_created = $client->messages->create(
        //     '+971543293292',
        //     ['+971503361319'],
        //     'hello there'
        //     // 'Howdy Glenn,
        //     // We`re excited that you`ve decided to sell your device to TronicsPay. We currently reviewing your application and we will get back to you as soon as possible. To print your free shipping label you can click here.
            
        //     // We also created an account for you, you can login at Member Login using these email aen00100@gmail.com with the password H4KybWoVI2.'
        // );
        // print_r($message_created);




        /**
         * Rapid API
         */
        // $curl = curl_init();

        // curl_setopt_array($curl, [
        //     CURLOPT_URL => "https://telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com/sms-verification-code?phoneNumber=%2B971503361319&verifyCode=1",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_HTTPHEADER => [
        //         "x-rapidapi-host: telesign-telesign-send-sms-verification-code-v1.p.rapidapi.com",
        //         "x-rapidapi-key: c8a5d53049mshc975f6f3daf6b26p112be4jsna78fc552d17a"
        //     ],
        // ]);
        
        // $response = curl_exec($curl);
        // $err = curl_error($curl);
        
        // curl_close($curl);
        
        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }
        
        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }

        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
        // exit; 





        /**
         * Messagebird
         */
        // // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://rest.messagebird.com/messages');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "recipients=+971503361319&originator=+971503361319&body=Hi! This is your first message.");

        // $headers = array();
        // $headers[] = 'Authorization: AccessKey HfIDBYwzbzKvk8obYtMkkKoWo';
        // $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);   
        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';
        
        // exit; 


        /**
         * GlobalSMS
         */
        // // initialize a new curl
        // $ch = curl_init();
        // $baseURL ="http://api.globalsms.ae/failsafe/HttpLink?username=uaepromo&pin=TRm@E8dh&";
        // $replyTo = "Ingizly"; //senderId
        // $messageBody = urlencode(('test only'));
        // $recipientNo = '+971503361319';
        // $URI = $baseURL;
        // $URI .= "signature=" . $replyTo; 
        // $URI .= "&mnumber=" . $recipientNo; 
        // $URI .="&message=" . $messageBody; 
        // // Set URL to connect to
        // curl_setopt($ch, CURLOPT_URL,$URI); 
        // // Set header supression
        // curl_setopt($ch, CURLOPT_HEADER,0); // Disable SSL peer verification
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // // Indicate that the message should be returned to a variable
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // // Make request
        // $content = curl_exec($ch);
        // $result = json_decode($content, true);
        // $responses = curl_getinfo($ch);
        // //print_r($responses);
        // if($responses['http_code']==200){
        //     //print_r($responses);
        //     return true;

        // }
        // curl_close($ch);
        // echo '<pre>';
        // print_r($responses);
        // echo '</pre>';
        
        // exit;
        



        /**
         * TWILIO
         */
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = $this->tablelist->twilio_sms_credential['account_sid'];
        $auth_token = $this->tablelist->twilio_sms_credential['auth_token'];
        $twilio_number = "+13347210661";
        $message = 'Howdy Glenn,
        We`re excited that you`ve decided to sell your device to TronicsPay. We currently reviewing your application and we will get back to you as soon as possible. To print your free shipping label you can click here.
        
        We also created an account for you, you can login at Member Login using these email aen00100@gmail.com with the password H4KybWoVI2.';
        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                '+971503361319',
                array(
                    'from' => $twilio_number,
                    'body' => $message
                )
            );
            echo '<pre>';
            print_r($client);
            echo '</pre>';
        
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        exit;




        /**
         * Messagebird
         */
        // $account_sid = 'ACXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
        // $auth_token = 'your_auth_token';
        // // In production, these should be environment variables. E.g.:
        // // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
        // // A Twilio number you own with SMS capabilities
        // $twilio_number = "+15017122661";
        // $client = new Client($account_sid, $auth_token);
        // $client->messages->create(
        //     // Where to send a text message (your cell phone?)
        //     '+15558675310',
        //     array(
        //         'from' => $twilio_number,
        //         'body' => 'I sent this message in under 10 minutes!'
        //     )
        // );





        // require_once __DIR__.'/../../../vendor/messagebird/php-rest-api/autoload.php';
        // $messageBird = new \MessageBird\Client('HfIDBYwzbzKvk8obYtMkkKoWo'); // Set your own API access key here.
        
        // $message             = new \MessageBird\Objects\Message();
        // $message->originator = 'YourBrand';
        // $message->recipients = ['+971503361319'];
        // $message->body       = 'This is a test message.';
        // // $messageBird->messages->create($message);
        // echo '<pre>';
        // print_r($message);
        // echo '</pre>';
        // try {
        //     $messageResult = $messageBird->messages->create($message);
        //     var_dump($messageResult);
        
        // } catch (\MessageBird\Exceptions\AuthenticateException $e) {
        //     // That means that your accessKey is unknown
        //     echo 'wrong login';
        
        // } catch (\MessageBird\Exceptions\BalanceException $e) {
        //     // That means that you are out of credits, so do something about it.
        //     echo 'no balance';
        
        // } catch (\Exception $e) {
        //     echo $e->getMessage();
        // }
        // exit;
        // return 'adsads';
        // return $message;

        
        
    }
}
