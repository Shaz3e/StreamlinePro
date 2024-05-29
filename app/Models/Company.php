<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use HasFactory, AuditingAuditable, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'email',
        'phone',
        'website',
        'country',
        'address',
        'is_active',
    ];

    // SoftDeletes
    protected $dates = ['deleted_at'];

    protected function setAuditInclude()
    {
        // Get all columns from the model's table
        $columns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());

        // Set the $auditInclude property to include all columns
        $this->auditInclude = $columns;
    }

    /**
     * Relationship with users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }    

    /**
     * Invoice Relationship
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
