<style>
	#logo {
		display: flex;
		align-items: center;
	}

	.logo-text {
		margin-left: -35px; /* Adjust this value as needed */
	}	

	#primary-menu a {
        text-decoration: none;
    }

	.dropdown:hover .dropdown-menu {
		display: block;
		right: 0;
	}

	.dropdown-menu .dropdown-item {
        color: black;
		padding: 7px;
		font-size: 12px;
	}

	.dropdown-item:hover {
        color: white;
		background-color: #fe6400;
	}
	nav.primary-menu a.menu-link,
	nav.primary-menu ul li span.menu-link {
		font-size: 10px;
		color: black;
	}

	.header-border-color: {
		rgba(var(--cnvs-contrast-rgb), .1);
	}

	/* Submenu styling */
	.dropdown-submenu .submenu {
		position: relative; /* keep it inside parent */
		padding-left: 1.5rem; /* indent the items */
	}

	/* Optional: if you want a little more offset for nested hover */
	.dropdown-submenu .submenu .dropdown-item {
		padding-left: 1rem; /* extra indent for text inside */
	}

</style>

<header id="header" class="full-header header-size-sm semi-transparent dark">
	<div id="header-wrap" style="background-color:#fe6400 ;">
		<div class="container">
			<div class="header-row justify-content-between justify-content-lg-start">

				<!-- Logo
				============================================= -->
				<div id="logo" class="m-2">
					<a href="{{ env('APP_URL') }}">
						<img class="logo-default" src="{{ asset('images/company-logo-white.png') }}" alt="Logo" style="height: 40px;">
					</a>
				</div><!-- #logo end -->

				<div class="mx-lg-3 mx-sm-0 d-none d-lg-block">
					<strong class="logo-text text-uppercase" style="font-size:17px; color:#ffffff">Inventory System</strong>
					{{-- <strong class="logo-text text-uppercase text-danger" style="font-size:17px;">TF LOGISTICS <span class="text-primary">PHILIPPINES</span></strong> --}}
				</div>

				<div class="primary-menu-trigger">
					<button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
						<span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
					</button>
				</div>

				<!-- Primary Navigation
				============================================= -->
					<nav class="primary-menu with-arrows ms-lg-auto me-lg-3">
						<ul class="menu-container menu-item">
							
							<li class="mx-1 dropdown">
								<a class="text-light menu-link" href="{{ route('home') }}"><i class="fa fa-home"></i> Dashboard</a>
							</li>
							<li class="mx-1 dropdown">
								<a class="text-light menu-link" href="{{ route('items.index') }}"><i class="bi-boxes bi-alt"></i> Manage Items</a>
								<div class="dropdown-menu bg-light shadow">
									<a class="dropdown-item" href="{{ route('items.index') }}">Items List</a>
									<a class="dropdown-item" href="{{ route('items.create') }}">Create New Item</a>
								</div>
							</li>

							<li class="mx-1 dropdown">
								<a class="text-light menu-link" href="#"><i class="uil-apps"></i> Transactions</a>
								<div class="dropdown-menu bg-light shadow p-0" style="min-width:280px;">

									<div class="dropdown-submenu">
										<a class="dropdown-item fw-bold">Requisitions</a>
										<div class="submenu bg-white">
											<a class="dropdown-item" href="{{ route('issuance.requisitions.index') }}">Material Requisition List</a>
											<a class="dropdown-item" href="{{ route('issuance.requisitions.create') }}">Create New Transaction</a>
										</div>
									</div>

									<div class="dropdown-divider m-0"></div>

									<div class="dropdown-submenu">
										<a class="dropdown-item fw-bold">Purchase Orders</a>
										<div class="submenu bg-white">
											<a class="dropdown-item" href="{{ route('receiving.purchase-orders.index') }}">Purchase Order List</a>
											<a class="dropdown-item" href="{{ route('receiving.purchase-orders.create') }}">Create New Order</a>
										</div>
									</div>

									<div class="dropdown-divider m-0"></div>

									<div class="dropdown-submenu">
										<a class="dropdown-item fw-bold">Receiving</a>
										<div class="submenu bg-white">
											<a class="dropdown-item" href="{{ route('receiving.transactions.index') }}">Receiving Transaction List</a>
											<a class="dropdown-item" href="{{ route('receiving.transactions.create') }}">Create New Transaction</a>
										</div>
									</div>

									<div class="dropdown-divider m-0"></div>

									<div class="dropdown-submenu">
										<a class="dropdown-item fw-bold">Issuance</a>
										<div class="submenu bg-white">
											<a class="dropdown-item" href="{{ route('issuance.transactions.index') }}">Issuance Transaction List</a>
											<a class="dropdown-item" href="{{ route('issuance.transactions.create') }}">Create New Transaction</a>
										</div>
									</div>
									
								</div>
							</li>
						
							<li class="mx-1 dropdown">
								<span class="text-light menu-link"><i class="bi-graph-up"></i> Reports</span>
								<div class="dropdown-menu bg-light shadow">
									<a class="dropdown-item" href="{{ route('reports.issuance') }}" target="_blank">Issuance Report</a>
									<a class="dropdown-item" href="{{ route('reports.receiving') }}" target="_blank">Receiving Stock Report</a>
									<a class="dropdown-item" href="{{ route('reports.receivables') }}" target="_blank">Receivables</a>
									<a class="dropdown-item" href="{{ route('reports.stock-card') }}" target="_blank">Stock Card Report</a>
									<a class="dropdown-item" href="{{ route('reports.inventory') }}" target="_blank">Inventory Report</a>
									<a class="dropdown-item" href="{{ route('reports.non-inventory') }}" target="_blank">Non-Inventory Report</a>
									<a class="dropdown-item" href="{{ route('reports.users') }}" target="_blank">User Report</a>
									<a class="dropdown-item" href="{{ route('reports.audit-trail') }}" target="_blank">Audit Trail</a>
									<a class="dropdown-item" href="{{ route('reports.items') }}" target="_blank">Item List</a>
									<a class="dropdown-item" href="{{ route('reports.deficit-items') }}" target="_blank">Below Minimum Stock</a>
								</div>
							</li>
						
							<li class="mx-1 dropdown" @if(auth()->user()->role_id != 1) hidden @endif>
								<span class="text-light menu-link"><i class="bi-gear"></i> Maintenance</span>
								<div class="dropdown-menu bg-light shadow">
									<a class="dropdown-item" href="{{ route('items.categories.index') }}">Item Categories</a>
									<a class="dropdown-item" href="{{ route('items.types.index') }}">Item Types</a>
									<a class="dropdown-item" href="{{ route('receiving.suppliers.index') }}">Suppliers</a>
									{{-- <a class="dropdown-item" href="{{ route('issuance.receivers.index') }}">Receivers</a> --}}
									<a class="dropdown-item" href="{{ route('issuance.vehicles.index') }}">Vehicles</a>
									<a class="dropdown-item" href="{{ route('accounts.users.index') }}">System Users</a>
									<a class="dropdown-item" href="{{ route('accounts.roles.index') }}">System Roles</a>
									<a class="dropdown-item" href="{{ route('accounts.permissions.index') }}">System Permissions</a>
									<a class="dropdown-item" href="{{ route('accounts.settings.index') }}">Other Settings</a>
								</div>
							</li>

							<li class="mx-1 dropdown m-2 d-none d-lg-block">
								<div class="bg-light fa-2x" style="width: 1px; opacity: 35%;">&nbsp;</div>
							</li>

							<!-- User profile dropdown integrated into the menu list -->
							<li class="mx-1 dropdown">
								<a href="#" class="text-light menu-link dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="{{ asset(Auth::user()->avatar ?? 'images/user.png') }}" alt="profile" class="rounded-circle" width="35px" height="35px" style="object-fit: cover;">
								</a>
								<ul class="dropdown-menu text-small shadow bg-light" aria-labelledby="dropdownUser">
									<li>
										<a class="dropdown-item disabled text-dark" href="#">
											<strong class="text-uppercase"><small><i class="fa fa-user"></i> {{ Auth::user()->name }}</small></strong>
										</a>
									</li>
									<li><a class="dropdown-item" href="{{ route('accounts.users.edit-profile') }}">Profile</a></li>
									<li><a href="{{route('logout')}}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-out"></i> Log Out</a></li>
								</ul>
							</li>
						</ul>
					</nav>
				<!-- #primary-menu end -->

				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
					<input type="hidden" name="role_id" value="{{Auth::user()->role_id }}">
				</form>

				<form class="top-search-form" action="search.html" method="get">
					<input type="text" name="q" class="form-control" value="" placeholder="Type &amp; Hit Enter.." autocomplete="off" style="height: 30px;">
				</form>

			</div>
		</div>
	</div>
	<div class="header-wrap-clone"></div>
</header>
	
{{-- MODAL --}}

@php
	$items = App\Models\Custom\Item::all();
@endphp

<div class="modal fade text-start funding-management-modal" tabindex="-1" role="dialog" aria-labelledby="centerModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Select a item</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				
				<div class="list-group">
					<select class="selectpicker" data-live-search="true" required>
						<option>-- SELECT A BOOK --</option>
						@foreach($items as $item)
							<option value="{{ $item->id }}">{{ $item->sku }}: {{ $item->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="list-group">
					{{-- <a href="{{ route('reports.stock-card', $item->id) }}" target="_blank"><button type="button" class="btn btn-success mt-2">Confirm</button></a> --}}
				</div>
			</div>
		</div>
	</div>
</div>
