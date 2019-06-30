<style>
    .navbar-toggleable-sm::after {
        display: none;
    }
    .header-navbar .navbar-container ul.nav li a.dropdown-user-link {
        font-size: 19px;
    }
</style>

<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header" style="display: flex;justify-content: center;align-items: center;">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
                <li class="nav-item"><a href="/dashboard" class="navbar-brand nav-link"><img height="40px" alt="branding logo" src="/images/logo.png" data-expand="/images/logo.png" data-collapse="/images/logo.png" class="brand-logo"></a></li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm" style="display: flex !important;justify-content: space-between;align-items: center;">
                <ul class="nav navbar-nav">
                    <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5">         </i></a></li>
                    <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-xs-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <i class="icon-user" style="font-size: 18px;"></i>
                            <span class="user-name" style="font-size: 18px;"><?= \Auth::user()->name; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <form id="logout_form" action="/logout" method="POST">
                                @csrf
                                <a href="#" class="dropdown-item logout-link"><i class="icon-power3"></i> Sair</a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>