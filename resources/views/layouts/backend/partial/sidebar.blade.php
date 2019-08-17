<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="./img/a1.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">SHOROL - Stock</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/a2.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <router-link to="/profile" class="d-block">{{ Auth::user()->name }} </router-link>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
 
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                  Dashboard
              </p>
            </router-link>
          </li>
         
        <li class="nav-item">
          <router-link to="/supplier" class="nav-link">
            <i class="nav-icon fas fa-industry"></i>
            <p>
                Supplier 
            </p>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/customer" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Customer 
            </p>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/category" class="nav-link">
            <i class="nav-icon fas fa-layer-group"></i>
            <p>
                Category 
            </p>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/product" class="nav-link">
            <i class="nav-icon fab fa-product-hunt"></i>
            <p>
                Product 
            </p>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/purchase" class="nav-link">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>
                Purchase || Stock
            </p>
          </router-link>
        </li>
        
        <li class="nav-item">
          <router-link to="/sales" class="nav-link">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                  Sales
              </p>
          </router-link>
      </li>

      <li class="nav-header">SETTINGS</li>

      <li class="nav-item">
        <router-link to="/user" class="nav-link">
          <i class="nav-icon fas fa-user text-success"></i>
          <p>
              All Admin 
          </p>
        </router-link>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
              <i style="color:red" class="nav-icon fas fa-power-off"></i>
            <p>Log Out</p>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>