<x-mail::message>
# Dear {{ $invoice->user->name ?? $invoice->company->name }},

This is the third billing notice that your invoice no. {{ $invoice->id }} which was generated on 
{{ $invoice->invoice_date ? $invoice->invoice_date->format('l, jS M Y') : $invoice->published_on->format('l, jS M Y') }} is now overdue. Failure to make payment will result in account suspension.
<br><br>

------------------------------------------------------
Invoice: {{ $invoice->id }}<br>
Balance Due: Rs{{ $invoice->total - $invoice->total_paid }}PKR<br>
Due Date: {{ $invoice->due_date->format('l, jS M Y') }}
------------------------------------------------------

You can login to your client area to view and pay the invoice at

@component('mail::button', ['url' => route('admin.invoices.show', $invoice->id)])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
