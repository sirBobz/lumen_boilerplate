<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TransactionRate extends Model
{
	protected $table = 'transaction_rates';

	protected $fillable = [
	    'org_id',
		'service_id',
		'rate',
	];

}