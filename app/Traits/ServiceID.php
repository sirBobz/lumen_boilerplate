<?php

namespace App\Traits;
use App\Models\Service;
use Cache;

trait ServiceID
{


     /**
     * Get service ID
     *
     * @return  @int ID
     */
     public function getServiceID($service_name)
     {
          return Cache::remember('getServiceID', 60, function() use ($service_name){
                        return Service::where('name', '=', $service_name)->value('id'); });
     }

}






