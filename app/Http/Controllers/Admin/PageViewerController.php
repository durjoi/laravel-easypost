<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Admin\PageMetaTagRepositoryEloquent as PageMetaTag;
use App\Repositories\Admin\PageBuilderRepositoryEloquent as PageBuilder;
use App\Models\TableList as Tablelist;

class PageViewerController extends Controller
{
    protected $configRepo;
    protected $pageMetaTagRepo;
    protected $pageBuilder;
    protected $tablelist;

    public function __construct (Config $configRepo, PageMetaTag $pageMetaTagRepo, PageBuilder $pageBuilder, Tablelist $tablelist) 
    {
        $this->configRepo = $configRepo;
        $this->pageMetaTagRepo = $pageMetaTagRepo;
        $this->pageBuilder = $pageBuilder;
        $this->tablelist = $tablelist;
    }

    public function MetaTags () 
    {
        $data['config'] = $this->configRepo->find(1);
        $data['tvpage'] = true;
        $data['tags'] = $this->tablelist->array_meta_tags;
        $data['pageBuilder'] = $this->pageBuilder->all();
        $data['module'] = 'config';
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
        return view('admin.pagemetatag.index', $data);
    }
}
