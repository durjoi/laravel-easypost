<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Customer\StateRepositoryEloquent as State;

class ConfigController extends Controller
{
    protected $configRepo;
    protected $stateRepo;

    function __construct(Config $configRepo, State $stateRepo)
    {
        $this->configRepo = $configRepo;
        $this->stateRepo = $stateRepo;
    }

    public function index()
    {
        $data['config'] = $this->configRepo->find(1);
        $data['stateList'] = $this->stateRepo->selectlist('name', 'abbr');
        return view('admin.settings.config.index', $data);
    }

    public function update(Request $request, $id)
    {
        $makeRequest = [
            'company_name' => $request['company_name'],
            'company_email' => $request['company_email'],
            'address1' => $request['address1'],
            'address2' => $request['address2'],
            'city' => $request['city'],
            'state' => $request['state'],
            'zip_code' => $request['zip_code'],
            'phone' => $request['phone'],
            'company_schedule' => $request['company_schedule'],
            'good' => $request['good'],
            'fair' => $request['fair'],
            'poor' => $request['poor']
        ];

        $this->configRepo->update($makeRequest, $id);
        return redirect()->back();
    }
}
