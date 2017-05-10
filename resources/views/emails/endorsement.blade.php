@extends('emails.template')
@section('content')
<?php $loan = $args['loan'];  ?>

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
                  	<div style="font-size:12px;line-height:14px;color:#444444;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><span style="font-size: 24px; line-height: 28px;"><strong><span style="line-height: 28px; font-size: 24px;">{{ config('preferences.notif_subjects.created') }}</span></strong></span></p></div>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->
                  
                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;"><![endif]-->
                  <div style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;">
                  	<div style="font-size:12px;line-height:14px;color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><span style="font-size: 16px; line-height: 19px;">
                      Mr./Ms. {{ ucwords(strtolower($utils->getFName($loan->endorser_FullName))) }},
                    </span></p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px"><span style="font-size: 16px; line-height: 19px;">A new EFund application with control number <strong>{{ $loan->ctrl_no }}</strong> has been submitted by {{ ucwords(strtolower($loan->FullName)) }} of {{ ucwords(strtolower($loan->DeptDesc)) }} Department.</span></p></div>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->

                  
                  
                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;"><![endif]-->
                  <div style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;">
                  	<div style="font-size:12px;line-height:14px;color:#777777;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px">The application is pending for your endorsement approval. To approve the application, please login to our Online EFund System by clicking on the Login button bellow.</p></div>
                  </div>
                  <!--[if mso]></td></tr></table><![endif]-->
                  
                  <div align="left" class="button-container left" style="padding-right: 10px; padding-left: 10px; padding-top:15px; padding-bottom:10px;">
                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top:15px; padding-bottom:10px;" align="left"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:42px; v-text-anchor:middle; width:146px;" arcsize="12%" strokecolor="#003333" fillcolor="#003333"><w:anchorlock/><center style="color:#ffffff; font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size:16px;"><![endif]-->
                      <a href="{{ route('endorsements.index') }}" style="text-decoration: none">
                      <div style="color: #ffffff; background-color: #003333; border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; max-width: 126px; width: 86px; width: 25%; border-top: 0px solid transparent; border-right: 0px solid transparent; border-bottom: 0px solid transparent; border-left: 0px solid transparent; padding-top: 5px; padding-right: 20px; padding-bottom: 5px; padding-left: 20px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; text-align: center; mso-border-alt: none;" >
                        <span style="font-size:16px;line-height:32px;">Login</span>
                      </div></a>
                    <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                  </div>
                  
                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;"><![endif]-->
                    <div style="padding-right: 10px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;">
                    	<div style="font-size:12px;line-height:14px;color:#777777;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px">You are receiving this email because you have been assigned as an endorser of the EFund applicant. For more information, please contact the EFund Custodian.</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">Thank you!</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">Regards,&nbsp;</p><p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;<br></p><p style="margin: 0;font-size: 14px;line-height: 16px">EFund System</p></div>
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