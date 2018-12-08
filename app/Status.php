<?php

namespace App;


class Status extends ReadOnlyBase
{

    protected $student_status           =   [
                                                ['Drop-out','text-danger'],
                                                ['Pursuing','text-primary'],
                                                ['Graduated','text-success'],
                                            ];

    protected $level_status             =   ['Not started', 'Pursuing', 'Transferred', 'Course Completed', 'Drop-out'];
    protected $attempt_type             =   ['Fresher', 'Repeater'];
    protected $admission_type           =   ['Fresh','Transferred'];

    protected $area_status              =   ['Publish', 'Unpublished'];

    protected $program_status           =   [
                                                ['Unpublished','text-danger'],
                                                ['Published', 'text-success']
                                            ];

    protected $course_status            =   [
                                                ['Unpublished','text-danger'],
                                                ['Published', 'text-success']
                                            ];

    protected $faculty_account_status   =   [
                                                ['Inactive', 'text-danger'],
                                                ['Active', 'text-success']
                                            ];

    protected $fee_collection_status    =   [
                                                ['Not Paid','text-muted'],
                                                ['Paid','text-success'],
                                                ['Buffered','text-primary'],
                                                ['Drafted',''],
                                                ['Payment Rejected By Admin','text-danger'],
                                            ];

    protected $fee_payment_type         =   ['Cash Pay','Force Closed'];

    protected $fee_type_status          =   [
                                                ['Unpublished','text-danger'],
                                                ['Published', 'text-success']
                                            ];

    protected $fee_status               =   [
                                                ['Inactive', 'text-danger'],
                                                ['Active', 'text-success'],
                                                ['Scheduled', 'text-primary']
                                            ];

    protected $franchisee_status        =   [
                                                ['Inactive', 'text-danger'],
                                                ['Active', 'text-success']
                                            ];

    protected $master_status            =   ['In active', 'Active'];

    protected $user_status              =   ['In active', 'Active'];

    protected $tc_status                =   ['Request raised', 'Processing', 'Approved', 'Rejected'];

    protected $qp_status                =   ['Request raised', 'Processing', 'Approved', 'Rejected'];

    protected $certificate_status       =   ['Request raised', 'Processing', 'Approved', 'Rejected'];

    protected $question_paper_status    =   [
                                                ["Request raised","text-muted"],
                                                ["Processing","text-primary"],
                                                ["Approved","text-success"],
                                                ["Rejected","text-danger"],
                                            ];

    protected $bill_book_status         =   [
                                                ["Inactive","text-warning"],
                                                ["Active","text-success"],
                                                ["Completed","text-secondary"]
                                            ];

}
