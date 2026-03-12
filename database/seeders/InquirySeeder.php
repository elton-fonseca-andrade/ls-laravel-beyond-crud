<?php

namespace Database\Seeders;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\AdmittedInquiryState;
use Domain\Inquiries\States\PendingInquiryState;
use Domain\Inquiries\States\RejectedInquiryState;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $inquiries = [
            [
                'name' => 'Anna Cooper',
                'email' => 'anna.cooper@example.com',
                'phone' => '+1-555-0101',
                'date_of_birth' => '1992-03-14',
                'reason' => 'Frequent anxiety and panic attacks',
                'notes' => null,
                'state' => PendingInquiryState::class,
            ],
            [
                'name' => 'Brian Oliver',
                'email' => 'brian.oliver@example.com',
                'phone' => '+1-555-0102',
                'date_of_birth' => '1985-07-22',
                'reason' => 'Post-divorce depression',
                'notes' => null,
                'state' => PendingInquiryState::class,
            ],
            [
                'name' => 'Clara Mitchell',
                'email' => 'clara.mitchell@example.com',
                'phone' => '+1-555-0103',
                'date_of_birth' => '1998-11-05',
                'reason' => 'Difficulty concentrating and insomnia',
                'notes' => null,
                'state' => AdmittedInquiryState::class,
            ],
            [
                'name' => 'Daniel Shaw',
                'email' => 'daniel.shaw@example.com',
                'phone' => '+1-555-0104',
                'date_of_birth' => '1979-01-30',
                'reason' => 'Grief after loss of a family member',
                'notes' => null,
                'state' => AdmittedInquiryState::class,
            ],
            [
                'name' => 'Emily Foster',
                'email' => 'emily.foster@example.com',
                'phone' => '+1-555-0105',
                'date_of_birth' => '2001-06-18',
                'reason' => 'Eating disorder',
                'notes' => null,
                'state' => AdmittedInquiryState::class,
            ],
            [
                'name' => 'Frank Reeves',
                'email' => 'frank.reeves@example.com',
                'phone' => '+1-555-0106',
                'date_of_birth' => '1990-09-25',
                'reason' => 'Severe occupational stress',
                'notes' => null,
                'state' => AdmittedInquiryState::class,
            ],
            [
                'name' => 'Grace Lawson',
                'email' => 'grace.lawson@example.com',
                'phone' => '+1-555-0107',
                'date_of_birth' => '1988-04-12',
                'reason' => 'Social phobia',
                'notes' => 'No available slots at this time',
                'state' => RejectedInquiryState::class,
            ],
            [
                'name' => 'Henry Marshall',
                'email' => 'henry.marshall@example.com',
                'phone' => '+1-555-0108',
                'date_of_birth' => '1995-12-03',
                'reason' => 'Substance dependency',
                'notes' => 'Referred to a specialized clinic',
                'state' => RejectedInquiryState::class,
            ],
            [
                'name' => 'Isabel Santos',
                'email' => 'isabel.santos@example.com',
                'phone' => '+1-555-0109',
                'date_of_birth' => '2003-08-20',
                'reason' => 'Self-esteem and interpersonal relationships',
                'notes' => null,
                'state' => PendingInquiryState::class,
            ],
            [
                'name' => 'James Parker',
                'email' => 'james.parker@example.com',
                'phone' => '+1-555-0110',
                'date_of_birth' => '1975-02-10',
                'reason' => 'Burnout syndrome',
                'notes' => null,
                'state' => PendingInquiryState::class,
            ],
        ];

        foreach ($inquiries as $data) {
            Inquiry::create($data);
        }
    }
}
