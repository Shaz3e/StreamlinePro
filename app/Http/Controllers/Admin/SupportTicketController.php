<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupportTicket\StoreSupportTicketRequest;
use App\Models\Admin;
use App\Models\Department;
use App\Models\SupportTicket;
use App\Models\SupportTicketPriority;
use App\Models\SupportTicketReply;
use App\Models\SupportTicketStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Models\Audit;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check Authorize
        Gate::authorize('support-ticket.list');

        return view('admin.support-ticket.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check Authorize
        Gate::authorize('support-ticket.create');

        // Get all active Staff/Admin
        $staffList = Admin::where('is_active', 1)->get();

        // Get all active Clients
        $clients = User::where('is_active', 1)->get();

        // Get all active Department
        $departments = Department::where('is_active', 1)->get();

        // Get all active suport ticket statuses
        $ticketStatuses = SupportTicketStatus::where('is_active', 1)->get();

        // Get all active suport ticket priority
        $ticketPriorities = SupportTicketPriority::where('is_active', 1)->get();

        return view('admin.support-ticket.create', [
            'staffList' => $staffList,
            'clients' => $clients,
            'departments' => $departments,
            'ticketStatuses' => $ticketStatuses,
            'ticketPriorities' => $ticketPriorities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportTicketRequest $request)
    {
        // Check Authorize
        Gate::authorize('support-ticket.create');

        // Validate data
        $validated = $request->validated();

        // Generate a Ticket Number
        $ticketNumber = 'TKT-' . time() . '-' . date('d-m-y');

        // Provide a ticket number as generated above
        $validated['ticket_number'] = $ticketNumber;

        // Retrieve all the uploaded images from the session variable
        $uploadedAttachments = session()->get('uploaded_attachments');

        if (!empty($uploadedAttachments)) {
            $validated['attachments'] = json_encode($uploadedAttachments);
        }

        // Update record in database
        SupportTicket::create($validated);

        session()->flash('success', 'Support Ticket has been created successfully!');

        return redirect()->route('admin.support-tickets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportTicket $supportTicket)
    {
        // Check Authorize
        Gate::authorize('support-ticket.read');

        // Get attachments
        $attachments = json_decode($supportTicket->attachments, true);

        // Get all admin/staff
        $staffList = Admin::where('is_active', 1)->get();

        // Get all departments
        $departments = Department::where('is_active', 1)->get();

        // Get all support ticket statuses
        $supportTicketStatus = SupportTicketStatus::where('is_active', 1)->get();

        // Get all support ticket priority
        $supportTicketPriorities = SupportTicketPriority::where('is_active', 1)->get();

        // Get all support ticket replies
        $supportTicketReplies = SupportTicketReply::where('support_ticket_id', $supportTicket->id)
            ->orderBy('id', 'asc')
            ->get();

        // Authorize check to view audits records
        $audits = $supportTicket->audits()
            ->latest()
            ->paginate(10);

        // ajax request to refresh audit log after delete
        if (request()->ajax()) {
            return response()->json($audits);
        }

        return view('admin.support-ticket.show', [
            'supportTicket' => $supportTicket,
            'attachments' => $attachments,
            'staffList' => $staffList,
            'departments' => $departments,
            'supportTicketStatus' => $supportTicketStatus,
            'supportTicketPriorities' => $supportTicketPriorities,
            'supportTicketReplies' => $supportTicketReplies,
            'audits' => $audits,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportTicket $supportTicket)
    {
        // Check Authorize
        Gate::authorize('support-ticket.update');

        // Get all active Staff/Admin
        $staffList = Admin::where('is_active', 1)->get();

        // Get all active Clients
        $clients = User::where('is_active', 1)->get();

        // Get all active Department
        $departments = Department::where('is_active', 1)->get();

        // Get all active suport ticket statuses
        $ticketStatuses = SupportTicketStatus::where('is_active', 1)->get();

        // Get all active suport ticket priority
        $ticketPriorities = SupportTicketPriority::where('is_active', 1)->get();

        return view('admin.support-ticket.edit', [
            'supportTicket' => $supportTicket,
            'staffList' => $staffList,
            'clients' => $clients,
            'departments' => $departments,
            'ticketStatuses' => $ticketStatuses,
            'ticketPriorities' => $ticketPriorities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSupportTicketRequest $request, SupportTicket $supportTicket)
    {
        // Check Authorize
        Gate::authorize('support-ticket.update');

        // Validate data
        $validated = $request->validated();

        // Generate a Ticket Number

        if ($request->hasFile('attachments')) {
            $ticketNumber = $supportTicket->ticket_number;
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = $ticketNumber . '.' . $file->extension();
                $attachments[] = $file->storeAs('support-tickets/attachments', $filename, 'public');
            }
            $validated['attachments'] = json_encode($attachments); // Serialize the array to a JSON string
        }

        // Update record in database
        $supportTicket->update($validated);

        // Flash message
        session()->flash('success', 'Todo Status has been updated successfully!');

        // Redirect to index
        return redirect()->route('admin.support-tickets.index');
    }

    /**
     * Show Audit Log
     */
    public function audit(Request $request)
    {
        // Check Authorize
        Gate::authorize('support-ticket.force.delete');

        if (request()->ajax()) {
            $auditLog = Audit::find($request->id);

            return view('admin.support-ticket.audit', [
                'auditLog' => $auditLog,
            ]);
        } else {
            session()->flash('error', 'Log not available');
            return redirect()->route('admin.support-tickets.index');
        }
    }

    /**
     * Delete Audit Log
     */
    public function deleteAudit(Request $request)
    {
        // Check Authorize
        Gate::authorize('support-ticket.force.delete');

        if (request()->ajax()) {
            $auditLog = Audit::find($request->id);
            $auditLog->delete();
            return response()->json(['status' => 1]);
        }

        session()->flash('success', 'Log deleted successfully');
        return redirect()->route('admin.support-tickets.index');
    }

    /**
     * Upload attachments
     */
    public function uploadAttachments(Request $request)
    {
        // Gate::authorize('support-ticket.force.create');
        // Gate::authorize('support-ticket.force.update');
        // Get the uploaded image
        $image = $request->file('attachments');

        // Store the uploaded image in a session variable
        session()->push('uploaded_attachments', $image->storeAs('support-tickets/attachments', time() . '.' . $image->extension(), 'public'));

        return response()->json(['message' => 'Image uploaded successfully!']);
    }

    /**
     * Support Ticket Reply
     */
    public function ticketReply(Request $request, SupportTicket $supportTicketId)
    {
        // Check Authorize
        Gate::authorize('support-ticket.read');

        // Update only status
        if($request->has('updateStatus')){
            // Validate data
            $validated = $request->validate([
                'admin_id' => 'required|exists:admins,id',
                'department_id' => 'required|exists:departments,id',
                'support_ticket_status_id' => 'required|exists:support_ticket_statuses,id',
                'support_ticket_priority_id' => 'required|exists:support_ticket_priorities,id',
            ]);

            $supportTicketId->update([
                'admin_id' => $request->admin_id,
                'department_id' => $request->department_id,
                'support_ticket_status_id' => $request->support_ticket_status_id,
                'support_ticket_priority_id' => $request->support_ticket_priority_id,
            ]);

            session()->flash('success', 'Support Ticket status been changed successfully!');

            return redirect()->route('admin.support-tickets.show', $supportTicketId->id);
        }

        // Validate data
        $validated = $request->validate([
            'message' => 'required',
            'attachments' => [
                'nullable',
                'array',
                'validate_each:mimes:jpeg,png',
                'max:2048',
            ],
        ]);

        // Update record in database
        $supportTicketReply = new SupportTicketReply();
        $supportTicketReply->support_ticket_id = $supportTicketId->id;
        $supportTicketReply->staff_reply_by = auth()->guard('admin')->user()->id;
        $supportTicketReply->message = $request->message;
        
        // Retrieve all the uploaded images from the session variable
        $uploadedAttachments = session()->get('uploaded_attachments');

        if (!empty($uploadedAttachments)) {
            $validated['attachments'] = json_encode($uploadedAttachments);
        }

        $supportTicketReply->save();

        session()->flash('success', 'Support Ticket Reply has been created successfully!');

        return redirect()->route('admin.support-tickets.show', $supportTicketId->id);
    }
}
