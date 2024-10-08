<?php

use App\Jobs\Admin\BulkEmail\SendBulkEmailJob;
use App\Jobs\Admin\BulkEmail\StoreBulkEmailJob;
use App\Jobs\PaymentMethod\NgeniusGatewayJob;
use App\Jobs\Staff\Task\TaskOverdueReminderJob;
use App\Jobs\Staff\Task\TaskReminderJob;
use App\Jobs\System\Task\DailyTaskReportJob;
use App\Jobs\User\Invoice\DailyInvoiceNotificationJob;
use App\Jobs\User\Invoice\GenerateRecurringInvoiceJob;
use App\Jobs\User\Invoice\OverdueReminder\InvoiceFirstOverDueNoticeJob;
use App\Jobs\User\Invoice\OverdueReminder\InvoiceSecondOverDueNoticeJob;
use App\Jobs\User\Invoice\OverdueReminder\InvoiceThirdOverDueNoticeJob;
use App\Jobs\User\Invoice\Reminder\InvoiceFirstReminderBeforeDueDateJob;
use App\Jobs\User\Invoice\Reminder\InvoiceSecondReminderBeforeDueDateJob;
use App\Jobs\User\Invoice\Reminder\InvoiceThirdReminderBeforeDueDateJob;
use App\Jobs\User\PromotionScheduleJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| php /path/to/your/project/artisan queue:work --sleep=3
|--------------------------------------------------------------------------
|
| On production make sure to adjust the path to your project directory
| The --sleep=3 option in the queue:work command is a parameter that controls
| how long the queue worker will sleep (or pause) between processing jobs.
| In this case, --sleep=3 means that the queue worker will sleep for 3 seconds between processing each job.
| The --sleep option is useful for a few reasons:
|   1) Prevents overload:
|       By introducing a short delay between jobs, you can prevent the queue worker from overwhelming
|       your application or server with too many requests at once.
|   2) Allows for queue recovery:
|       If the queue worker crashes or is restarted,
|       the --sleep option ensures that it will wait for a short period before processing new jobs,
|       allowing the queue to recover and preventing duplicate processing.
|   3) Improves performance:
|       By introducing a brief pause between jobs, you can help prevent performance issues caused by rapid-fire job processing.
*/

/**
 * Send task reminders to respective staff at regular intervals.
 *
 * Intervals:
 * - Every 15 minutes
 * - Every 1 hour
 * - Every 6 hours
 * - Every 12 hours
 * - Every 24 hours
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendTaskReminderJob') == 1) {
    Schedule::job(new TaskReminderJob)->everyFifteenMinutes();
}

/**
 * If the deadline has passed since the creation of the task.
 * Send task overdue reminder to assigned_to and created_by
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendTaskOverdueReminderJob') == 1) {
    Schedule::job(new TaskOverdueReminderJob)->daily();
}

/**
 * Send daily task report to DiligentCreators('notification_email)
 */
if (Schema::hasTable('app_settings') && DiligentCreators('DailyTaskReportJob') == 1) {
    Schedule::job(new DailyTaskReportJob)->daily();
}

/**
 * Send Invoice as email when published_date equals today
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('DailyInvoiceNotificationJob') == 1) {
    Schedule::job(new DailyInvoiceNotificationJob)->daily();
}

/**
 * Send Invoice First Reminder when due_date 3 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceFirstReminderBeforeDueDateJob') == 1) {
    Schedule::job(new InvoiceFirstReminderBeforeDueDateJob)->daily();
}

/**
 * Send Invoice First Reminder when due_date 2 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceSecondReminderBeforeDueDateJob') == 1) {
    Schedule::job(new InvoiceSecondReminderBeforeDueDateJob)->daily();
}

/**
 * Send Invoice First Reminder when due_date 1 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceThirdReminderBeforeDueDateJob') == 1) {
    Schedule::job(new InvoiceThirdReminderBeforeDueDateJob)->daily();
}

/**
 * Send Invoice First Overdue Reminder when due_date after 1 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceFirstOverDueNoticeJob') == 1) {
    Schedule::job(new InvoiceFirstOverDueNoticeJob)->daily();
}

/**
 * Send Invoice Second Overdue Reminder when due_date after 2 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceSecondOverDueNoticeJob') == 1) {
    Schedule::job(new InvoiceSecondOverDueNoticeJob)->daily();
}

/**
 * Send Invoice Third Overdue Reminder when due_date after 3 days from now
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendInvoiceThirdOverDueNoticeJob') == 1) {
    Schedule::job(new InvoiceThirdOverDueNoticeJob)->daily();
}

/**
 * Generate Recurring Invoice with duplicated data
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('GenerateRecurringInvoiceJob') == 1) {
    Schedule::job(new GenerateRecurringInvoiceJob)->daily();
}

/**
 * Update Promotion is_active status based on time
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('PromotionScheduleJob') == 1) {
    Schedule::job(new PromotionScheduleJob)->daily();
}

/**
 * Check N-Geniuse Network Gateway Payment via reference and update
 * and Delete reference once payment is updated
 * do not run if model is empty
 * Intervals: Every Minute
 */
if (Schema::hasTable('app_settings') && DiligentCreators('NgeniusGatewayJob') == 1) {
    Schedule::job(new NgeniusGatewayJob)->everyMinute();
}

/**
 * Store single record from bulk_emails table into emails table
 * Intervals: Every Minute
 */
if (Schema::hasTable('app_settings') && DiligentCreators('StoreBulkEmailJob') == 1) {
    Schedule::job(new StoreBulkEmailJob)->everyMinute();
}

/**
 * Send Emails from emails table
 * Intervals: Daily
 */
if (Schema::hasTable('app_settings') && DiligentCreators('SendEmailJob') == 1) {
    Schedule::job(new SendBulkEmailJob)->daily();
}
