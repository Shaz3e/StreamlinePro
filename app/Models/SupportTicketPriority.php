<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class SupportTicketPriority extends Model implements Auditable
{
    use HasFactory, AuditingAuditable, SoftDeletes;

    protected $guarded = [];

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
     * Relationship with Support Tickets Table
     */
    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'support_ticket_priority_id');
    }
}
