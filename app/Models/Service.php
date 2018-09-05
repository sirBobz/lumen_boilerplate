<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * 
 * @property int $id
 * @property string $name
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $callbacks
 * @property \Illuminate\Database\Eloquent\Collection $commissions
 * @property \Illuminate\Database\Eloquent\Collection $organization_accounts
 * @property \Illuminate\Database\Eloquent\Collection $third_party_credentials
 *
 * @package App\Models
 */
class Service extends Model
{

	protected $fillable = [
		'name'
	];

}
