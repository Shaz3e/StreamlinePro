<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoice_labels = [
            [
                'name' => 'Draft',
                'description' => 'The invoice is being prepared and hasn\'t been sent to the client yet.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sent',
                'description' => 'The invoice has been sent to the client but hasn\'t been paid yet.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pending',
                'description' => 'The client has not yet made a payment for the invoice.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Partial Payment',
                'description' => 'The client has made a partial payment for the invoice amount.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paid',
                'description' => 'The client has paid the full amount of the invoice.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Overdue',
                'description' => 'The invoice has not been paid by the due date.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Void',
                'description' => 'The invoice has been canceled or invalidated.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Processing',
                'description' => 'The payment for the invoice is currently being processed.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refunded',
                'description' => 'The payment for the invoice has been refunded to the client.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pending Approval',
                'description' => 'The invoice is pending approval before it can be sent to the client.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pending Review',
                'description' => 'The invoice is pending review by an administrator or manager.',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('invoice_labels')->insert($invoice_labels);
    }
}
