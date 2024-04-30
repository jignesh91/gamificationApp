<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
     // Static property to store the current tenant ID
     protected static $currentTenantId = null;

     // Method to set the current tenant ID
     public static function setCurrentTenantId($tenantId)
     {
         self::$currentTenantId = $tenantId;
     }
 
     // Method to get the current tenant ID
     public static function getCurrentTenantId()
     {
         return self::$currentTenantId;
     }
}
