<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Admin\PageRepositoryEloquent as Page;
use App\Repositories\Admin\BrandRepositoryEloquent as Brand;
use App\Repositories\Admin\PageRowRepositoryEloquent as PageRow;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Admin\PageColumnRepositoryEloquent as PageColumn;
use App\Repositories\Admin\PageStaticRepositoryEloquent as PageStatic;
use App\Repositories\Admin\PageContentRepositoryEloquent as PageContent;
use App\Repositories\Admin\PageSectionRepositoryEloquent as PageSection;
use App\Repositories\Admin\ProductPhotoRepositoryEloquent as ProductPhoto;
use App\Repositories\Customer\StateRepositoryEloquent as State;

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

    function __construct(Page $pageRepo, PageSection $sectionRepo, PageRow $rowRepo, PageColumn $columnRepo, PageContent $contentRepo, PageStatic $staticRepo, Product $productRepo, ProductPhoto $productPhotoRepo, Brand $brandRepo, State $stateRepo)
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
    }

    public function welcome()
    {
        $data['page'] = $this->staticRepo->findByField('page_id', 1);
        $data['rowone'] = $this->brandRepo->rawAll("feature = ?", [1]);
        $data['rowtwo'] = $this->brandRepo->rawAll("feature = ?", [2]);
        $data['rowtri'] = $this->brandRepo->rawAll("feature = ?", [3]);
        return view('welcome', $data);
    }

    public function aboutus()
    {
        $data['page'] = $this->staticRepo->findByField('page_id', 2);
        return view('front.aboutus', $data);
    }

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

    public function products()
    {
        $data['products'] = $this->productRepo->rawWith(['photo' => function($query){
            $query->first();
        }], "status = ?", ['Active']);
        return view('front.products', $data);
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
}
