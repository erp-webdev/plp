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
						<i id="main_icon" class="fa fa-bars" style="color: #004296"></i>
					</a>
				</li>
			</ul>
			<ul class="sidebar-nav" id="sidebar">
				<!-- Dashboard -->
				<a class="btn btn-primary sidebar-menu <?php if(Session::get('menu')=='dashboard') echo 'active'?>" role="button" href="{{ route('admin.dashboard') }}">
  					DashBoard <i class="fa fa-tachometer pull-right"></i>
				</a>
				<a class="btn btn-primary sidebar-menu <?php if(in_array(Session::get('menu'), ['users', 'myaccount', 'preferences','faq'])) echo 'active'?>" data-toggle="collapse" aria-expanded="false" aria-controls="settings" href="#settings">
  					Settings <i class="fa fa-gears pull-right"></i>
				</a>
				<div class="collapse <?php if(in_array(Session::get('menu'), ['users', 'myaccount', 'preferences','faq'])) echo 'in'?>" id="settings">
					<!-- submenu -->
					<!--
					<a class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='myaccount') echo 'active'?>" href="{{ route('account.edit', Auth::user()->id) }}">
					    My Account<i class="fa fa-user pull-right"></i>
					</a>  -->
				@role('Admin')
					<a class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='users') echo 'active'?>" href="{{ route('users.index') }}">
					    Users<i class="fa fa-group pull-right"></i>
					</a>
				@endrole
				@permission('Preferences')
					<!-- <a class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='preferences') echo 'active'?>" href="{{ route('preferences.index') }}">
					    Preferences<i class="fa fa-gear pull-right"></i> -->
					</a>
				@endpermission
					<a class="btn btn-primary sidebar-submenu <?php if(Session::get('menu')=='faq') echo 'active'?>" href="{{ url('admin/documentation') }}">
					    FAQ<i class="fa fa-question pull-right"></i>
					</a>
					<a class="btn btn-primary sidebar-submenu" href="{{ url('/') }}/logout">
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
