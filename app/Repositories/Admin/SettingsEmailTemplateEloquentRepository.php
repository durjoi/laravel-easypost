<?php

namespace App\Repositories\Admin;

use App\Models\Admin\SettingsEmailTemplate;

class SettingsEmailTemplateEloquentRepository extends AbstractRepository implements SettingsEmailTemplateRepository
{
	protected $query;

	function __construct(SettingsEmailTemplate $query)
	{
		$this->query = $query;
	}

    public function datatablesIndex()
    {
        return $this->query->whereRaw("1=?", [1]);
    }
}