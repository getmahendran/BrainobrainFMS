<?php

namespace App;

class ReadOnlyBase
{
    protected $source                   =   [];
    protected $question_paper_status    =   [];
    protected $program_status           =   [];
    protected $course_status            =   [];
    protected $fee_status               =   [];
    protected $franchisee_status        =   [];
    protected $faculty_account_status   =   [];
    protected $student_status           =   [];
    protected $fee_collection_status    =   [];
    protected $fee_payment_type         =   [];
    protected $bill_book_status         =   [];

    public function allSources(){
        return $this->source;
    }
    public function getSource($id){
        return $this->source[$id];
    }

    public function allFranchiseStatus()
    {
        return $this->franchisee_status;
    }
    public function getFranchiseeStatus($id)
    {
        return $this->franchisee_status[$id];
    }

    public function allFacultyStatus()
    {
        return $this->faculty_account_status;
    }
    public function getFacultyStatus($id)
    {
        return $this->faculty_account_status[$id];
    }

    public function allQuestionPaperRequestStatus()
    {
        return $this->question_paper_status;
    }
    public function getQuestionPaperRequestStatus($id, $index)
    {
        return $this->question_paper_status[$id][$index];
    }

    public function allProgramStatus()
    {
        return $this->program_status;
    }
    public function getProgramStatus($id)
    {
        return $this->program_status[$id];
    }

    public function allCourseStatus()
    {
        return $this->course_status;
    }
    public function getCourseStatus($id)
    {
        return $this->course_status[$id];
    }

    public function allFeeStatus()
    {
        return $this->fee_status;
    }
    public function getFeeStatus($id)
    {
        return $this->fee_status[$id];
    }

    public function allStudentStatus()
    {
        return $this->student_status;
    }
    public function getStudentStatus($id)
    {
        return $this->student_status[$id];
    }
    public function getStudentStatusIndex($value)
    {
        foreach($this->student_status as $key => $status)
        {
            if ( $status[0] === $value )
                return $key;
        }
        return sizeof($this->student_status);
    }

    public function allFeeCollectionStatus()
    {
        return $this->fee_collection_status;
    }
    public function getFeeCollectionStatus($id)
    {
        if(isset($id))
            return $this->fee_collection_status[$id];
        else
            return "NA";
    }

    public function allFeePaymentTypes()
    {
        return $this->fee_payment_type;
    }
    public function getFeePaymentType($id)
    {
        if(isset($id))
            return $this->fee_payment_type[$id];
        else
            return "NA";
    }

    public function allBillBookStatus()
    {
        return $this->bill_book_status;
    }
    public function getBillBookStatus($id){
        $this->bill_book_status[$id];
    }
}