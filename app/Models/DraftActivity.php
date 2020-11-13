<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftActivity extends BaseModel
{
   protected $guarded = [];
   
   protected $table = 'draft_invoices_activities';
}
