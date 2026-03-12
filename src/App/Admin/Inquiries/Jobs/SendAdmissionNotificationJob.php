<?php

namespace App\Admin\Inquiries\Jobs;

use Domain\Inquiries\Models\Inquiry;
use Domain\Patients\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdmissionNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Inquiry $inquiry,
        public readonly Patient $patient,
    ) {}

    public function handle(): void
    {
        Mail::raw(
            "Dear {$this->inquiry->name}, your inquiry has been admitted. Your patient ID is {$this->patient->id}.",
            fn ($message) => $message
                ->to($this->inquiry->email)
                ->subject('Inquiry Admitted'),
        );
    }
}
