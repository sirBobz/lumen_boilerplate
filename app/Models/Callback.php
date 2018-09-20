<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Callback extends Model
{
	protected $table = 'callbacks';

	protected $fillable = [
		'service_id',
		'org_id',
		'callbackurl',
		'validation_url'
	];

}