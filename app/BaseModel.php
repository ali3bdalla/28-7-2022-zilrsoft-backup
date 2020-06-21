<?php


namespace App;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

//        if (auth()->check() &&
//                (new static())
//                ->getConnection()
//                ->getDoctrineColumn("organization_id")
//                ->hasColumn('organization_id')
//        )
//            static::addGlobalScope(
//                (new class  implements Scope{
//                    public $organization_id;
//                    public function __construct($organization_id)
//                    {
//                        $this->organization_id = $organization_id;
//                    }
//                    public function apply(Builder $builder, Model $model)
//                    {
//                        $model->where('organization_id',$this->organization_id);
//                    }
//                })(auth()->user()->organization_id)
//             );

    }
}