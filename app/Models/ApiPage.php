<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property int $page
 * @property string $created_at
 * @property string $updated_at
 */
class ApiPage extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'api_page';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['page', 'created_at', 'updated_at'];

}
