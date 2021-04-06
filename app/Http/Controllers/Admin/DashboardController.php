<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;

class DashboardController extends Controller
{
    protected $configRepo;

    public function __construct(Config $configRepo)
    {
        $this->configRepo = $configRepo;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['module'] = 'dashboard';
        $config = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($config['is_dark_mode'] == 1) ? true : false;
        return view('admin.dashboard.index', $data);
    }
}


