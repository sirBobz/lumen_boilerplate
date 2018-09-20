<?php

namespace App\Traits;
use App\Models\OrganizationAccount;

trait OrganizationID
{

     /**
     * Get Organization id 
     *
     * @return  @int id
     */
     public function getOrganizationID($request)
     {
          return Cache::remember('organization_id', 10, function() use ($request){
                 return OrganizationAccount::where('account', $request->BusinessShortCode)
                      ->where('service_id', Service::where('name', '=', 'mpesa_c2b')->value('id'))
                      ->value('org_id');
                    });
     }

}