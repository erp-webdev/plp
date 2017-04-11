<script>
function setWrapperStatus(){
	$.get("{{ url('/') }}/wrapper");
}
</script>
<div id="sidebar-wrapper">
			<ul id="sidebar_menu" class="sidebar-nav" style="background-color: #fff;">
				<li class="sidebar-brand" style="padding: 0px; margin: 0px; border-bottom: 0px" style="background-color: #fff;">
					<a style="background-color: #fff;" id="menu-toggle" href="{{ route('account.edit', Auth::user()->id) }}" onclick="setWrapperStatus();">
						<img src="{{ url('nav-brand.png') }}" height="62px;">
						<i id="main_icon" class="fa fa-bars" style="color: #003333"></i>
					</a>
				</li>
			</ul>
			<ul class="sidebar-nav" id="sidebar">
				<!-- Dashboard -->
				<a id="dashboardMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='dashboard') echo 'active'?>" role="button" href="{{ route('admin.dashboard') }}">
  					DashBoard <i class="fa fa-tachometer pull-right"></i>
				</a>
				@permission(['application_list', 'application_view', 'application_create', 'application_edit', 'application_delete'])
				<a id="myEfunds" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='myloans') echo 'active'?>" role="button" href="{{ route('applications.index') }}">
  					My eFunds <img src="{{ url('efund_sm.png') }}" width="20px" class="pull-right">
				</a>
				@endpermission
				@permission(['loan_list', 'loan_view', 'loan_edit'])
				<a id="loansMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='loans') echo 'active'?>" role="button" href="{{ route('admin.loan') }}">
  					Transactions <i class="fa fa-list pull-right"></i>
				</a>
				<a  id="ledgerMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='ledger') echo 'active'?>" role="button" href="{{ route('ledger.index') }}">
  					Ledger <i class="fa fa-calculator pull-right"></i>
				</a>
				@endpermission
				@permission(['payroll'])
				<a id="payrollMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='payroll') echo 'active'?>" role="button" href="{{ route('payroll.index') }}">
  					Payroll <i class="fa fa-check-square-o pull-right"></i>
				</a>
				@endpermission
				@permission(['treasurer'])
				<a id="treasurerMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='treasury') echo 'active'?>" role="button" href="{{ route('treasury.index') }}">
  					Treasury <i class="fa fa-list pull-right"></i>
				</a>
				@endpermission
				@if(\eFund\Endorser::endorsements()->count() > 0)
				<a id="endorsementsMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='endorsements') echo 'active'?>" role="button" href="{{ route('endorsements.index') }}">
  					Endorsements <i class="fa fa-thumbs-up pull-right"></i>
				</a>
				@endif
				@if(\eFund\Guarantor::guarantors()->count() > 0)
				<a id="guarantorMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='guarantors') echo 'active'?>" role="button" href="{{ route('guarantors.index') }}">
  					Guarantors <i class="fa fa-money pull-right"></i>
				</a>
				@endif
				@permission(['custodian'])
				<a id="reportsMenu" class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='reports') echo 'active'?>" role="button" href="{{ route('report.index') }}">
  					Reports <i class="fa fa-book pull-right"></i>
				</a>
				@endif
				<a id="settingsMenu" class="btn btn-primary sidebar-menu <?php if(in_array(Session::get('menu'), ['users', 'myaccount', 'preferences','faq'])) echo 'active'?>" data-toggle="collapse" aria-expanded="false" aria-controls="settings" href="#settings">
  					Settings <i class="fa fa-cogs pull-right"></i>
				</a>
				<div class="collapse <?php if(in_array(Session::get('menu'), ['users', 'myaccount', 'preferences','faq'])) echo 'in'?>" id="settings">
					<!-- submenu -->
					<!--
					<a class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='myaccount') echo 'active'?>" href="{{ route('account.edit', Auth::user()->id) }}">
					    My Account<i class="fa fa-user pull-right"></i>
					</a>  -->
				@role('Admin')
					<a id="usersMenu" class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='users') echo 'active'?>" href="{{ route('users.index') }}">
					    Users<i class="fa fa-group pull-right"></i>
					</a>
				@endrole
				@permission('Preferences')
					<a id="prefMenu" class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='preferences') echo 'active'?>" href="{{ route('preferences.index') }}">
					    Maintenance<i class="fa fa-sliders pull-right"></i>
					</a>
				@endpermission
					<a id="docMenu" class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='faq') echo 'active'?>" href="{{ url('admin/documentation') }}">
					    Documentation<i class="fa fa-question pull-right"></i>
					</a>
					<a id="tutMenu" class="btn btn-primary sidebar-submenu" onclick="restartTour()">
					    Take a tour!<i class="fa fa-map-marker pull-right"></i>
					</a>
					<a id="logoutMenu" class="btn btn-primary sidebar-submenu" href="{{ url('/') }}/logout">
					    Logout<i class="fa fa-sign-out pull-right"></i>
					</a>
				</div>
				<!-- <li><a href="">Manage<span class="sub_icon glyphicon glyphicon-link"></span></a></li> -->
				<div class="col-md-12 col-xs-12 col-sm-12" style="color:#e7e7e7;font-size: 12px;padding-right: 50px">
					<br>Hi, <br>
					<strong>{{ Auth::user()->name }}</strong> <br>
					{{ Auth::user()->employee_id }}
				</div>
				<div class="col-md-12 col-xs-12 col-sm-12" style="color:#e7e7e7;font-size: 12px;padding-right: 50px">
					<hr>
					eFund <i style="font-size: 10px">(v1.0)</i><br>
					Megaworld Corporation <br>ISM Department <br>All Rights Reserved © <?php echo date('Y'); ?> <br>
				</div>
			</ul>
		</div>
