// Instance the tour
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

// Initialize the tour
this.tour.init();

// Start the tour
this.tour.start();

