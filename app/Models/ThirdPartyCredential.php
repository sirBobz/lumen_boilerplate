<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 03 Jul 2017 15:18:24 +0300.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ThirdPartyCredential
 * 
 * @property int $id
 * @property int $service_id
 * @property int $org_id
 * @property string $cred
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Organization $organization
 * @property \App\Models\Service $service
 *
 * @package App\Models
 */
class ThirdPartyCredential extends Model
{

	protected $casts = [
		'service_id' => 'int',
		'org_id' => 'int'
	];

	protected $fillable = [
		'service_id',
		'org_id',
		'cred',
		'account'
	];


}
