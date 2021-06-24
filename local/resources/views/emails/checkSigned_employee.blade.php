@extends('emails.template')
@section('content')
<?php
	$loan = $args['loan'];
	$utils = $args['utils'];
?>
<div style="background-color:transparent;">
      <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;width: 500px;width: calc(19000% - 98300px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
        <div style="border-collapse: collapse;display: table;width: 100%;">
          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->

            <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:30px; padding-bottom:30px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num12" style="min-width: 320px;max-width: 500px;width: 500px;width: calc(18000% - 89500px);background-color: transparent;">
              <div style="background-color: transparent; width: 100% !important;">
              <!--[if (!mso)&(!IE)]><!--><div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:30px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->


                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px;"><![endif]-->
                  <div style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px;">
                  	<div style="font-size:12px;line-height:14px;color:#444444;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><span style="font-size: 24px; line-height: 28px;"><strong><span style="line-height: 28px; font-size: 24px;">{{ config('preferences.notif_subjects.check_signed_cust') }}</span></strong></span></p></div>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->

                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;"><![endif]-->
                  <div style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;">
                  	<div style="font-size:12px;line-height:14px;color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><span style="font-size: 16px; line-height: 19px;">
                      Mr./Ms. {{ ucwords(strtolower($utils->getFName($loan->FullName))) }},
                    </span></p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px"><span style="font-size: 16px; line-height: 19px;">
                    We are pleased to inform you that you may now claim your check at the Treasury Department on {{ date('j F Y', strtotime($loan->check_released)) }}. Your schedule of deductions per payroll cutoff will be sent once your check has been issued and released. </span></p></div>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->


                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;"><![endif]-->
                    <div style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;">
                    	<div style="font-size:12px;line-height:14px;color:#777777;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px">For more information, please contact Personal Loan Application Custodian.</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">Thank you!</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">Regards,&nbsp;</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">Personal Loan Application System</p></div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->

              <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
              </div>
            </div>
          <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->

        </div>
      </div>
    </div>
@endsection
