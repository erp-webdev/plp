/*===================================================
=            Megaworld EFund System Tour            =
=               by BootstrapTour.js                 =
=                                                   =
= This is a user interface and navigation tutorial  =
= that aims to help user on how to use the online   =
= Employees' Fund system of Megaworld Corp.         =
= To initialize this sections of this tutorial,     =
= see specific init and start function that can be  =
= found on the respected pages of the tour section. =
=                                                   =
===================================================*/

/*====================================================
=            General tour on Sidebar Menu            =
====================================================*/

var tour = new Tour({
  name: 'Efund_Tour_Gen',
  steps: [
    {
      element: "",
      title: "<strong>Hi, " + username + "!</strong>",
      content: "Welcome to Megaworld EFund Online System! <br> Follow steps of this tutorial to get familiar on how to use this system. Click <strong>End tour</strong> if you like to skip tutorial. Are your ready? Click <strong>Next</strong>!",
      onNext: function(){
        $('.fa fa-bars').addClass('tour-step-element-reflex');
      },
      backdrop: true,
      backdropContainer : '#sidebar',
    },
    {
      element: "#menu-toggle",
      title: "Main Menu",
      content: "Click <i class='fa fa-bars'></i> to expand or hide Sidebar menu. Try it!",
      backdrop: true,
      backdropContainer : '#sidebar-wrapper',
    },
    {
      element: "#dashboardMenu",
      title: "Dashboard",
      content: "Statistics and Notifications are being displayed here. However, content such as charts and messages varies based on your roles.",
      backdrop: true,
      backdropContainer : '#sidebar',
    },
  ],
orphan: false,
onEnd: function(){
  window.location.reload();
}
});

if($('#myEfunds').length){
  tour.addStep(
    {
      element: "#myEfunds",
      title: "My eFunds",
      content: "This where you can submit your eFund applications. Your eFunds are listed here. You can also monitor of their status as they are being approved. Click the menu button to learn more! Click Next to skip.",
      backdrop: true,
      backdropContainer : '#sidebar',
    });
}

if($('#loansMenu').length){
  tour.addStep({
    element: "#loansMenu",
    title: "Transactions",
    content: "All EFund applications are listed and monitored here. EFund Officer can approve applications here.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#ledgerMenu').length){
  tour.addStep({
    element: "#ledgerMenu",
    title: "Employees' Ledger",
    content: "Ledger of all employees who has a loan applications are listed here.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#reportsMenu').length){
  tour.addStep({
    element: "#reportsMenu",
    title: "EFund Reports",
    content: "Generate and print reports here.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#payrollMenu').length){
  tour.addStep({
    element: "#payrollMenu",
    title: "Payroll",
    content: "Verify Applications and view payroll deductions.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#treasurerMenu').length){
  tour.addStep({
    element: "#treasurerMenu",
    title: "Treasury",
    content: "Prepare and submit check and voucher information.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#endorsementsMenu').length){
  tour.addStep({
    element: "#endorsementsMenu",
    title: "Endorsements",
    content: "Approve loan application endorsements here.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#guarantorMenu').length){
  tour.addStep({
    element: "#guarantorMenu",
    title: "Guarantors",
    content: "Approve loan applications that you are co-borrowing.",
    backdrop: true,
    backdropContainer : '#sidebar',
  });
}

if($('#usersMenu').length){
  tour.addStep({
    element: "#usersMenu",
    title: "User Management",
    content: "Manage users, roles and permissioins here.",
    backdrop: true,
    backdropContainer : '#sidebar',
    onShow: function(){
      $('#settingsMenu').removeClass('collapsed');
      $('#settingsMenu').addClass('active');
      $('#settingsMenu').attr('aria-expanded', 'true');
      $('#settings').addClass('in');
    }
  });
}

if($('#prefMenu').length){
  tour.addStep({
    element: "#prefMenu",
    title: "Maintenance",
    content: "Manage EFund System settings and preferences.",
    backdrop: true,
    backdropContainer : '#sidebar',
    onShow: function(){
      $('#settingsMenu').removeClass('collapsed');
      $('#settingsMenu').addClass('active');
      $('#settingsMenu').attr('aria-expanded', 'true');
      $('#settings').addClass('in');
    }
  });
}

tour.addStep({
  element: "#docMenu",
  title: "Documentation",
  content: "Read the system documentation for more information.",
  backdrop: true,
  backdropContainer : '#sidebar',
  onShow: function(){
      $('#settingsMenu').addClass('active');
    $('#settingsMenu').removeClass('collapsed');
    $('#settingsMenu').attr('aria-expanded', 'true');
      $('#settings').addClass('in');
  }
});

tour.addStep({
  element: "#tutMenu",
  title: "Tutorial",
  content: "Click here to play through the tutorial again.",
  backdrop: true,
  backdropContainer : '#sidebar',
  onShow: function(){
    $('#settingsMenu').addClass('active');
    $('#settingsMenu').removeClass('collapsed');
    $('#settingsMenu').attr('aria-expanded', 'true');
    $('#settings').addClass('in');
  }
});

tour.addStep({
  title: "Congratulations!",
  content: "You have completed this tutorial!",
  backdrop: true,
  backdropContainer : '#sidebar',
  onShow: function(){
    $('#settingsMenu').addClass('active');
    $('#settingsMenu').removeClass('collapsed');
    $('#settingsMenu').attr('aria-expanded', 'true');
    $('#settings').addClass('in');
  }
});

/*=====  End of General Tour on Sidebar Menu  ======*/


/*======================================
=            Dashboard Tour            =
======================================*/



/*=====  End of Dashboard Tour  ======*/

/*======================================
=            My EFunds Tour            =
======================================*/

// Index
var MyEFund_index = [
  {
      element: "#refreshBtn",
      title: "Refresh",
      content: "Click this to refresh the page and reset search filters.",
      backdrop: true,
      backdropContainer : '#app-layout',
      
    },
    {
      element: "table",
      title: "EFund Application Listing",
      content: "All your applications are listed here. You can monitor your application's progress by looking at the status indicated.",
      placement: 'top',
      backdrop: true,
      backdropContainer : '#app-layout',
    },
    {
      element: ".btn-success:contains('Apply Loan')",
      title: "Applying a Loan",
      content: "Click this button to create and submit a new or reavailment loan applications. Try it!",
      reflex: true,
      // next: -1,
      backdrop: true,
      backdropContainer : '#wrapper',
    }
];


var MyEFund_create = [
    {
      element: $("span:contains('Type of Application')").closest('.col-md-6'),
      title: "Type of Application and Previous Balance",
      content: "By default, type of application is already determined by the system, so you don't have to choose here. If this is your first time to apply, click New else Reavailment. <br><br> Your previous balance is displayed here. This must always be 0.00 to proceed to application.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
      prev: -1,
    },
    {
      element: $("input[name='loc']").closest('.form-group'),
      title: "Local / Direct Line #",
      content: "Provide your local number or direct line.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='term_mos']").closest('.form-group'),
      title: "Terms",
      content: "Select number of months to pay your loan. Your first loan application of the year can be set to up 12 months while second availment can only be paid until December of the same year.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='loan_amount']").closest('.form-group'),
      title: "Loan Amount",
      content: "Enter loan amount. Your loan amount range varies base on your position as indicated below the input box. Loan amount above minimum requires you to provide your guarantor as well.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='interest']").closest('.form-group'),
      title: "Interest",
      content: "Interest is the loan interest percentage set by EFund Administrator.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('Total')").closest('.form-group'),
      title: "Total",
      content: "Total is the total amount to be deducted on your account.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('# of payments to be made*')").closest('.form-group'),
      title: "Number of payments",
      content: "Twice the terms you set is the number of payments to be made. Payment is twice a month or every payroll cut-off.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('Every payroll deductions*')").closest('.form-group'),
      title: "Deductions",
      content: "Automatic deductions to be made from your salary every cut-off until your loan is fully paid.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("h4:contains('Employee Information ')"),
      title: "Employee Information",
      content: "Click the arrow down to expand your employment information.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='head']").closest('div.col-md-4'),
      title: "Endorser",
      content: "Enter Employee ID of your Immediate Head or Department head who will be your endorser.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'top',
      onNext: function(){
        if($('#surety').attr('style') == 'display: none'){
          myEF2.addStep(
            {
              element: $("input[name='loan_amount']").closest('.form-group'),
              title: "Activating Guarantor",
              content: "Try providing a loan amount above minimum to activate the guarantor.",
              backdrop: true,
              backdropContainer : '#app-layout',
              placement: 'bottom',
              reflex: true,
              onNext: function(){
                if($("input[name='loan_amount']").val() <= $("input[name='loan_amount']").attr('min')){
                  myEF2.prev();
                }
              }
            });
        }
      }
    },
    {
        element: $("#surety_input").closest('div#surety'),
        title: "Guarantor",
        content: "Enter employee ID of your guarantor. This is required if you have a loan amount above minimum.",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'bottom',
        reflex: true,
        orphan: false,
        onShow: function(){
          $("input[name='loan_amount']").val($("input[name='loan_amount']").attr('min') + 500);
        }
      },
      {
        element: $("#verify"),
        title: "Verifying your Application",
        content: "Click this button to verify and validate your applications. This will inform you if you can submit the form or check data for corrections.",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'top',
        onShow: function(){
          $('form').attr('action', '');
          $('#verify').removeAttr('disabled');
        }
      },
      {
        element: $("#submit"),
        title: "Submitting your Application",
        content: "Click this button to submit your applications. You cannot modify your application form once submitted. Your application will be received first by your endorser.  ",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'top',
        onShow: function(){
          $('form').attr('action', '');
          $('#submit').removeAttr('disabled');
        }
      },
      {
        title: "Loan Application",
        content: "Once you submitted an application, it will be provided with a control number. You shall received an email if a check is ready for your claiming. A schedule of payroll deductions is also included in the email.",
        backdrop: true,
        backdropContainer : '#app-layout',
      },
      {
        title: "Payroll Deductions",
        content: "EFund Custodian shall update your ledger every payroll cut-off until you are fully paid.",
        backdrop: true,
        backdropContainer : '#app-layout',
      },
      {
        title: "Fully Paid",
        content: "You will be notified once your application has been fully paid. You may now apply for a new application.",
        backdrop: true,
        backdropContainer : '#app-layout',
      }
];

/*=====  End of My EFunds Tour  ======*/


/*=========================================
=            Transactions Tour            =
=========================================*/

var Transaction_index = [
  {
    element: "table",
    title: "Transactions Listing",
    content: "All EFund applications are listed and monitored here. It provides a summary of applications and their corresponding status.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
  },
  {
    element: $('#search').closest('div.input-group'),
    title: "Search Bar",
    content: "You can search transactions here by providing Ctrl No, Employee ID, or Date of application.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'left',
  },
  {
    element: $('a.btn-info:contains("Batch Deductions")'),
    title: "Batch Processing of Deductions",
    content: "You can process deductions of all employees with schedule of deductions on the set date.",
    backdrop: true,
    backdropContainer : '#app-layout',
    orphan: true,
  },
  {
    element: $('input[name="deductionDate"]').closest('.form-group'),
    title: "Deduction Date",
    content: "Chosee date of deductions. This will retrieve all emloyees with loan deductions on the set date.",
    backdrop: true,
    backdropContainer : '#app-layout',
    onShow: function(){
      $('.modal').attr('style', 'z-index:5000');
      $('.modal-backdrop').attr('style', 'z-index:1000');
    },
    orphan: true,
  },
  {
    element: $('input[name="d_arno"]').closest('.form-group'),
    title: "AR Number",
    content: "AR number is required to process the deductions.",
    backdrop: true,
    backdropContainer : '#app-layout',
    orphan: true,
  },
  {
    element: $('input[name="save"]'),
    title: "Applying the Deductions",
    content: "Clicking this button will apply the deductions with the AR # to all the listed employees. Applied deductions are automatically posted in the employee's ledger respectively.",
    backdrop: true,
    backdropContainer : '#app-layout',
    orphan: true,
  },
  {
    element: $('i.fa-upload').closest('a'),
    title: "Importing Existing Data",
    content: "Import existing data to the EFund system. Importing data is critical to the system and must follow proper data formats. Imported data can not be undone.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'left',
    orphan: true,
  }
];

/*=====  End of Transactions Tour  ======*/

/*===================================
=            Ledger Tour            =
===================================*/

var Ledger_steps_index = [
  {
    element: "table",  
    title: "Employees' Ledgers",
    content: "Employee's ledger is automatically posted here once they applied for a loan.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
   {
    element: $('i.fa.fa-eye').closest('a'),  
    title: "View Employee's Ledger",
    content: "View individual employee's ledger.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'left',
    orphan: true
  },
];

var Ledger_steps_show = [
  {
    element: ".table-hover",  
    title: "Employees' Ledger",
    content: "All Employee's Efund History are listed here.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: $('i.fa.fa-eye-slash').closest('a'),  
    title: "Hiding Balance",
    content: "Toggle display of all balance history or the current balance only.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: $('i.fa.fa-print').closest('a'),  
    title: "Printing",
    content: "Ledger can be generated in different format which includes HTML, PDF, and Excel. Click this button to generate it in HTML format.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: ".dropdown-toggle",  
    title: "Printing",
    content: "To generate the ledger in PDF or Excel, click here.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: ".input-group",  
    title: "Filtering Ledger",
    content: "Generate a specific or filtered ledger based on application date.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: "input[name='from']",  
    title: "Filtering Ledger",
    content: "Select starting date to generate ledger.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: "input[name='to']",  
    title: "Filtering Ledger",
    content: "Select end date to generate ledger.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: ".btn .btn-success",  
    title: "Filtering Ledger",
    content: "Click to begin filtering ledger.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  }
];

/*=====  End of Ledger Tour  ======*/


/*====================================
=            Payroll Tour            =
====================================*/

var Payroll_steps_index = [
  {
    element: "table",  
    title: "Payroll Verification",
    content: "Listing of Efund applications for payroll verifications",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: "#deductionList",  
    title: "Deductions List",
    content: "Generate a list of active efund applications and with deduction schedule.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'bottom',
    orphan: true
  }
];

/*=====  End of Payroll Tour  ======*/

/*=====================================
=            Treasury Tour            =
=====================================*/

var Treasury_steps_index = [
  {
    element: "table",  
    title: "Check and Voucher",
    content: "Listing of all approved Efund applications that are for encoding of check and voucher.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  }
];
/*=====  End of Treasury Tour  ======*/

/*====================================
=            Reports Tour            =
====================================*/

var Report_steps_index = [
  {
    element: ".list-group",  
    title: "Report Types",
    content: "Click specific report type you want to generate.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: "#filter",  
    title: "Filtering Report",
    content: "Provides different filter options to customize Efund reports.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: $('i.fa.fa-filter').closest('a'),  
    title: "Filtering Report",
    content: "Click this to generate a filtered report.",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
  {
    element: "#reportView",  
    title: "Report Viewer",
    content: "Your specified report type is displayed here. ",
    backdrop: true,
    backdropContainer : '#app-layout',
    placement: 'top',
    orphan: true
  },
];

/*=====  End of Reports Tour  ======*/

/*==================================
=            Users Tour            =
==================================*/



/*=====  End of Users Tour  ======*/

/*========================================
=            Maintenance Tour            =
========================================*/



/*=====  End of Maintenance Tour  ======*/

/*================================================
=            Misc Tools and Functions            =
================================================*/

function restartTour() {
    // General / Sidebar
    var tour = new Tour({
      name: 'Efund_Tour_Gen',
    });
    tour.restart(); 

    // Application
    tour = new Tour({
      name: 'EFund_Tour_App1',
    });
    tour.restart(); 

    // Application
    tour = new Tour({
      name: 'EFund_Tour_App2',
    });
    tour.restart(); 
    
    // Loans
    tour = new Tour({
      name: 'EFund_Tour_loan',
    });
    tour.restart(); 

    // Ledger
    tour = new Tour({
      name: 'Ledger_Tour_index',
    });
    tour.restart(); 

     // Payrolll
    tour = new Tour({
      name: 'Payroll_Tour_index',
    });
    tour.restart(); 

     // Treasury
    tour = new Tour({
      name: 'Treasury_Tour_index',
    });
    tour.restart(); 

     // Reports
    tour = new Tour({
      name: 'Reports_Tour_index',
    });
    tour.restart(); 
    
    window.location.reload();
}

/*=====  End of Misc Tools and Functions  ======*/


/*=====  End of Megaworld EFund System Tour  ======*/



