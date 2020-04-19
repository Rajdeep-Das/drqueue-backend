<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'address','postcode','landmark','lat','lon','website'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//    protected $hidden = [
//        'password',
//    ];

    /**
     * The attributes excluded from the mass assignable.
     *
     * @var array
     */
     protected $guarded = ['superadmin'];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id', 'id');
    }

}
