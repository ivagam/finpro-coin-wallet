@php $admin = data_get(session('user'), 'is_admin'); @endphp

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/finxcore-logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/finxcore-logo.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/finxcore-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="sidebar-menu-group-title">
                <a  href="{{ route('dashboard.index') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>               
            </li>
            <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Wallet</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                    <a href="{{ route('transfer') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Send Token</a>
                    </li>
                    <li>
                    <a href="{{ route('transferHistory') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Transaction History</a>
                    </li>    
                    <li>
                    <a href="{{ route('depositReport') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Deposit</a>
                    </li> 
                    @if($admin == 0)    
                    <li>
                    <a href="{{ route('withdrawalReport') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Withdrawal</a>
                    </li> 
                    @endif          
                </ul>
            </li>
            @if($admin == 1)
            <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Withdrawal</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                    <a href="{{ route('approvedstatus') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Approved</a>
                    </li>
                    <li>
                    <a href="{{ route('pendingstatus') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Pending</a>
                    </li>                    
                </ul>
            </li>
            <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Mint</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                    <a href="{{ route('mint') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Mint value</a>
                    </li>
                    <li>
                    <a href="{{ route('mintReport') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Mint Report</a>
                    </li>                    
                </ul>
            </li>
            <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Burn</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                    <a href="{{ route('burn') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Burn value</a>
                    </li>
                    <li>
                    <a href="{{ route('burnReport') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Burn Report</a>
                    </li>                    
                </ul>
            </li>
              <li class="dropdown">
                <a  href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
                  <ul class="sidebar-submenu">
                    <li>
                    <a href="{{ route('dashboard.userList') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> User List</a>
                    </li>
                                     
                </ul>
                
            </li>
            @endif
           
           
        </ul>
    </div>
</aside>