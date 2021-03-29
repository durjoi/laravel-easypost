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


<!-- 
<nav class="navbar th-75 nav-border navbar-light fixed-top navbar-expand-sm" style="background: rgb(255, 255, 255);">
  <button type="button" aria-label="Toggle navigation" class="navbar-toggler collapsed" aria-expanded="false" aria-controls="nav-text-collapse" style="overflow-anchor: none;">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-brand m-brand">
    <img src="{{ url('assets/images/logo.png') }}" class="d-inline-block align-top" />
  </div>
  <div id="nav-text-collapse" class="navbar-collapse collapse" style="display: none;">
    <ul class="navbar-nav w-full justify-content-center">
      <div class="navbar-brand d-brand pr-15">
        <img src="{{ url('assets/images/logo.png') }}" class="d-inline-block align-top" />
      </div>
      <li class="nav-item">
        <a href="{{ url('/') }}" aria-current="page" class="{{ navactive('/') }}" target="_self">Home</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('about-us') }}" class="{{ navactive('about-us') }}" target="_self">About Us</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('how-it-works') }}" class="{{ navactive('how-it-works') }}" target="_self">How It Works</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('contact-us') }}" class="{{ navactive('contact-us') }}" target="_self">Contact Us</a>
      </li>
      <li class="form-inline">
        <form class="form-inline">
        
          @if(isset($isValidAuthentication) && $isValidAuthentication == false)
          <a href="{{ url('customer/auth/login') }}" target="_self" class="btn btn-warning btn-md my-2 my-sm-0">Member Login</a>
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
</nav> -->