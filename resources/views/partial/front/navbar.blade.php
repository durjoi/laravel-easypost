<nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-light fixed-top padtb15">
  <div class="container"> 
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ url('assets/images/logo.png') }}" class="d-inline-block align-top" />
    </a> 
    <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4">
        <span class="navbar-toggler-icon"></span>
    </button>


    <div class="collapse navbar-collapse" id="navbar4">
      <ul class="navbar-nav mr-auto pl-lg-4">
        <li class="nav-item px-lg-2 "> 
        
            <a href="{{ url('/') }}" aria-current="page" class="{{ navactive('/') }}" target="_self">Home</a>
        </li>
        <li class="nav-item px-lg-2"> 
            <a href="{{ url('about-us') }}" class="{{ navactive('about-us') }}" target="_self">About Us</a>
        </li>

        <li class="nav-item px-lg-2">
            <a href="{{ url('how-it-works') }}" class="{{ navactive('how-it-works') }}" target="_self">How It Works</a>
        </li>
        <li class="nav-item px-lg-2"> 
            <a href="{{ url('contact-us') }}" class="{{ navactive('contact-us') }}" target="_self">Contact Us</a>
        </li>

      </ul>
      <ul class="navbar-nav ml-auto mt-3 mt-lg-0">
        <li class="nav-item"> 
            <form class="form-inline">
             
              @if(isset($isValidAuthentication))
                  @if(isset($isValidAuthentication) && $isValidAuthentication == false)
                      <a href="{{ url('customer/auth/login') }}" target="_self" class="btn btn-warning btn-md my-2 my-sm-0">Member Login</a>
                  @elseif(isset($isValidAuthentication) && $isValidAuthentication == true)
                      <a href="{{ url('customer/dashboard') }}" target="_self" class="btn btn-warning btn-md my-2 my-sm-0">Back to Dashboard</a>
                  @endif
              @endif
              <a href="{{ url('cart') }}" style="text-decoration: none; color: #000">
                <div style="margin-left: 20px" id="cart-counter">
                  <i class="fas fa-shopping-cart fa-fw"></i> <span></span>
                </div>
              </a>
            </form>
        </li>
      </ul>
    </div>
  </div>
</nav>