<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrganizationsFloat extends Model
{
	protected $table = 'organizations_float';

	protected $fillable = [
	    'org_id',
		'reserved_balance',
		'available_balance',
	];

}