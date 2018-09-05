<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $parent
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $api_credentials
 * @property \Illuminate\Database\Eloquent\Collection $callbacks
 * @property \Illuminate\Database\Eloquent\Collection $commissions
 * @property \Illuminate\Database\Eloquent\Collection $organization_accounts
 * @property \Illuminate\Database\Eloquent\Collection $organizations_addresses
 * @property \Illuminate\Database\Eloquent\Collection $organizations_floats
 * @property \Illuminate\Database\Eloquent\Collection $third_party_credentials
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Organization extends Model
{

	protected $fillable = [
		'name',
		'type',
		'parent'
	];

}
