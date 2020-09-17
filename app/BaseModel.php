<?php


namespace App;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        $tableName = (new static())->getColu;

        $table = with(new static())->getTable();
        $columns = Schema::getColumnListing($table);
       if (auth()->check() && in_array('organization_id',$columns ))
           static::addGlobalScope(new OrganizationScope);
            //    (new class  implements Scope{
            //        public $organization_id;
            //        public function __construct($organization_id)
            //        {
            //            $this->organization_id = $organization_id;
            //        }
            //        public function apply(Builder $builder, Model $model)
            //        {
            //            $model->where('organization_id',$this->organization_id);
            //        }
            //    })(auth()->user()->organization_id)
            // );

    }
}