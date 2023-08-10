<?php
namespace App\Console\Commands;

use App\Mail\ReminderMail;
use Illuminate\Console\Command;
use App\Models\Task_Details;
use App\Models\UsersModel;

use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    protected $signature = 'send:reminders';

    protected $description = 'Send reminder emails to users on due date on task';

    public function handle()
    {
        //pulling all task whose due date in today
        $tasks = Task_Details::whereDate('due_date', now()->format('Y-m-d'))->whereIn('taskStatus',['Pending','Under Process'])->get();
        foreach($tasks as $task) {
            //pulling useremail from user table using userId
            $useremail = UsersModel::where('id',$task->userId)->first();
            Mail::send('emails.reminder', ['task' => $task], function ($message) use ($useremail) {
                $message->from('taskinfo41@gmail.com');
                $message->to($useremail->email)->subject('Reminder: Task Due Today');
            });
        }

        $this->info('Reminder emails sent successfully.');
    }
}
