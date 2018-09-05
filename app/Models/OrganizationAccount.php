<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:23 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrganizationAccount
 * 
 * @property int $id
 * @property string $account
 * @property string $status
 * @property string $billing_type
 * @property int $org_id
 * @property int $service_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Service $service
 * @property \App\Models\Organization $organization
 *
 * @package App\Models
 */
class OrganizationAccount extends Model
{
	protected $casts = [
		'org_id' => 'int',
		'service_id' => 'int'
	];

	protected $fillable = [
		'account',
		'status',
		'billing_type',
		'org_id',
		'owner',
		'service_id'
	];

	public function service()
	{
		return $this->belongsTo(\App\Models\Service::class);
	}

	public function organization()
	{
		return $this->belongsTo(\App\Models\Organization::class, 'org_id');
	}
}
