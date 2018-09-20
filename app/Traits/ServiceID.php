<?php

namespace App\Traits;
use App\Models\Service;

trait ServiceID
{


     /**
     * Get service ID
     *
     * @return  @int ID
     */
     public function getC2bServiceID()
     {
          return Cache::remember('service_id', 30, function() use ($request){
                        return Service::where('name', '=', 'mpesa_c2b')->value('id'); });
     }

}






