<?php

namespace eFund\Utilities;
use DB;
use eFund\User;
use eFund\Loan;
use eFund\Schedule;
use eFund\Preference;
use Log;
class Utils
{
	private $stats = [	"Saved"                           , // [0]
						"For Co-Borrower's Approval"      , // [1]
						"For Endorser's Approval"         , // [2]
    					"For Officer's Approval"          , // [3]
                        "Treasury"                        , // [4]
                        "For Cheque Release"              , // [5]
    					"Incomplete"                      , // [6]
    					"Paid"                            , // [7]
                        "Denied"                            // [8]
    				 ];

    private $types  = [	"New",
    					"Reavailment"
    				 ];

    private $approverStat = [
                                "Denied",
                                "Approved",
                                'For Approval'
                            ];

    private $treasuryStat = [
                                "For Approval",
                                "Check Releasing",
                                "Released",
                                "Unknown Status"
                            ];

	/**
	 *
	 * Get Loan Application Type 
	 * @param  int $index  index [0, 1]
	 * @return   string 
	 *
	 */
   	public function getType($index)
   	{
   		return $this->types[$index];
   	}

   	/**
   	 *
   	 * Generate Loan Control Number
   	 * Format: YYYY-MM-XXXX
   	 * XXXX => Count of Loan Applications within the current year
   	 * @return string 
   	 * 
   	 */
   	public function generateCtrlNo()
    {
        // Ctrl No: YYYY-MM-XXXX
        $loans = Loan::yearly()->count();
        $loans = $loans + 1;

        return date('Y') . '-' . date('m') . '-' . str_pad($loans, 4, '0', STR_PAD_LEFT);
    }

    /**
     *
     * Get Loan Application Status
     * @param int $index Status index
     * @return string 
     *
     */
    public function getStatus($index)
    {
    	return $this->stats[$index];
    }

    /**
     *
     * Set Loan Application Status
     * Process Flow of Applications
     * [0] => Saved
     * [1] => For Co-Borrower's Approval
     * [2] => For Head's Approval
     * [3] => For Officer's Approval
     * [4] => Treasury
     * [5] => Paid (Complete)
     * @param int $status Status index
     * @param int $withSurety Loan with Co-borrower/surety
     * @return int 
     *
     */
    public function setStatus($status = null, $withSurety = null)
    {
    	if($status == null){
    		return 0;

    	}else if($status == 0){

    		if($withSurety > 0)
    			return 1;
    		else
    			return 2;

    	}else if($status == 1){

    		return 2;

    	}else if($status == 2){

    		return 3;

    	}else if($status == 3){

    		return 4;

    	}else if($status == 4){

    		return 5;

    	}else if($status == 5){

            return 6; 
    	}
        else if($status == 6){

            return 7; 

        }else{

            return 8;
        }

    }

    /**
     *
     * Format status
     * @param int $status Status index
     * return html
     *
     */
    public function formatStatus($status)
    {
        $label = 'default';

        if(in_array($status, [1,2]))
            $label = 'info';
        else if(in_array($status, [3]))
            $label = 'primary';
        else if(in_array($status, [4]))
            $label = 'warning';
        else if(in_array($status, [5,7]))
            $label = 'success';
        else if(in_array($status, [8]))
            $label = 'danger';


        return '<label class="label label-'. $label .'">'. $this->getStatus($status) .'</label>';
    }

    /**
     *
     * Get Approval Status
     * @param int $status Status index
     * @return string
     *
     */
    public function getApprovalStatus($status)
    {
        if($status == null)
            return $this->approverStat[2];        

        if($status < 3)
            return $this->approverStat[$status];

        return $this->appoverStat[2];
    }

    /**
     *
     * Format Approval Status based on Efund Status
     * @param int $status Approver status
     * @param int $eFundStat eFund Status
     * @param int $role Role status
     * @return string
     *
     */
    public function formatApprovalStatus($status, $eFundStat, $role)
    {
        $label = 'default';
        
        if($status == null)
            $label = 'primary';
        else if(in_array($status, [0]))
            $label = 'danger';
        else if(in_array($status, [1]))
            $label = 'success';
        else 
            $label = 'default';

        return '<label class="label label-'. $label .'">'. $this->getApprovalStatus($status) .'</label>';
    }

    /**
     *
     * Format Treasury status
     * @param int $status eFundStatus
     *
     */
    public function formatTreasuryStatus($status)
    {
        $label = 'default';
        
        if($status == 4){
            $label = 'primary';
            $status = 0;
        }else if($status == 5){
            $label = 'info';
            $status = 1;
        }else if($status == 6 || $status == 7){
            $label = 'success';
            $status = 2;
        }else{
            $label = 'default';
            $status = 3;
        }

        return '<label class="label label-'. $label .'">'. $this->treasuryStat[$status] .'</label>';
    }

    /**
     *
     * Get Total Loan
     * total = Loan Amount + interest amount
     * @param float $amount Loan amount
     * @param float $interest Interest percentage
     * @return float 
     *
     */
    public function getTotalLoan($amount, $interest, $mos)
    {
        return $amount + ($amount * ($interest / 100) * $mos);
    }

    /**
     *
     * Generate reference number
     * @return string
     *
     */
    public function generateReference()
    {
		return strtoupper(round(microtime(true) * 1000));
    }

    /**
     *
     * Get number of months allowed
     * @return int
     *
     */
    public function getTermMonths()
    {
    	$mos = Preference::name('payment_term');
        return $mos->value - date('n');
    }

    /**
     *
     * Compute deductions per payroll cutoff
     * @param int $terms Number of term months
     * @param float $loan Loan amount
     * @return float
     *
     */
    public function computeDeductions($terms, $loan)
    {
    	$int = Preference::name('interest');
    	return round( ($loan + ($loan * ($int->value/100) * $terms)) / ($terms * 2), 2); 
    }

    /**
     *
     * Format date
     * @param date $date Date
     * @return string
     */
    public function formatDate($date)
    {
        return date('j F y', strtotime($date));
    }


    /**
     *
     * Ut8 Encoder
     * @param object $obj Object to encode to utf8
     * @return object
     *
     */
    public function toUtf8($obj)
    {
        foreach ($obj as $key => $value) { 
            if(is_object($value))
                $obj->$key = $this->toUtf8($value);
            elseif(is_array($value))
                $obj[$key] = $this->toUtf8($value);
            else
                $obj->key = utf8_encode($value);
        }

        return $obj;
    }

    /**
     *
     * Get start of deduction base on check release date
     * @param date $date Start of Deduction
     * @return date 
     *
     */
    public function getStartOfDeduction($date)
    {
        $date = new \DateTime($date);
        $date->format('Y-m-d');

        $day = date_format($date, 'd');
        $month = date_format($date, 'm');
        $year = date_format($date, 'Y');
        $t = date_format($date, 't');

        if($day >= 6 && $day <= 20){

            return $date->setDate($year, $month, $t); // End Of Month EOM

        }
        else if($day >= 21 || $day <= 5){
            $date->setDate($year, $month, 15);
            
            if($day >= 21){
                $date->modify('+1 month');
            }

            return $date;

        }
    }

    /**
     *
     * Get User information
     * @param int $userId User Account ID
     * @return eFund\User
     *
     */
    public function getUserInfo($userId)
    {
        $user = User::find($userId);
        if(!empty($user))
            return $user;
        else
            return 'User not found!';
    }

    /**
     *
     * Format number with thousand separator and decimal places
     * @param float $value Number to be formatted
     * @param int $decimal Number of decimal places
     * @param char $separator Thousand separatory symbol
     * @param char $dot Decimal separator symbol
     * @return string
     *
     */
    public function formatNumber($value, $decimal = 2, $separator = ',', $dot = '.')
    {
        return number_format($value, $decimal, $dot, $separator);
    }

    /**
     *
     * Format Ledger
     * Identify redundant data and
     * @param string $value Value to be formatted
     * @param int $ctr Counter
     * @return string
     *
     */
    
    // Previous value passed by Ledger Formatter
    public $prevValue = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
    public $index = 0;
    public $prevCtr = 0;
    public function formatLedger($value, $ctr)
    {
        if($this->prevCtr != $ctr){
            $this->index = 0;
            $this->prevCtr = $ctr;
        }

        if($this->prevValue[$this->index] != $value)
            $this->prevValue[$this->index] = $value;
        else 
            $value = '';

        $this->index += 1;

        return $value;
    }


}

