<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\MenuRepositoryEloquent as Menu;

class MenuController extends Controller
{
    protected $menuRepo;

    function __construct(Menu $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function index()
    {
        $data['menus'] = $this->menuRepo->all();
        $data['module'] = 'menu';
        return view('admin.settings.menus.index', $data);
    }
}
