<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServicioUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'servicio_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'servicio_users';

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['servicio', 'user'];

    /**
     * Get the bes for the pivot model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    /**
     * Get the product for the pivot model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
