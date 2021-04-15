@extends('layouts.front')
@section('content')
    
				 
    <section class="">
        <section class="hero">
            <div class="container">
                <div class="text-center pt-50 hero-top">
                    <h1 class="hero-header">Send In Your Mobile Device &amp; Get Paid.</h1>
                    <div class="search-input">
                        <form action="{{ url('products') }}" method="POST">
                            @csrf
                            <input type="text" name="productname" placeholder="Search for your device..." class="form-control" /><br />
                            <button type="submit" class="btn btn-warning btn-md hvr-shrink">Search</button>
                        </form>
                    </div>
                </div>
                <div class="row mt-50">
                    @if(isset($rowone))
                        @foreach($rowone as $row)
                        <div class="col-lg-2 col-md-4 device-link">
                            <a href="{{ url('products/category', $row->name) }}" class="hvr-shrink">
                                <img src="{{ url($row->photo) }}" class="img-fluid" />
                                <h4 class="device-name">{{ $row->name }}</h4>
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="row mt-50">
                    <div class="col-lg-1"></div>
                    @if(isset($rowtwo))
                        @foreach($rowtwo as $row)
                        <div class="col-lg-2 col-md-4 device-link">
                            <a href="{{ url('products/brand', $row->name) }}" class="hvr-shrink">
                                <img src="{{ url($row->photo) }}" class="img-fluid" />
                                <h4 class="device-name">{{ $row->name }}</h4>
                            </a>
                        </div>
                        @endforeach
                    @endif
                    <div class="col-lg-1"></div>
                </div>
                <div class="row mt-50 pb-50">
                    <div class="col-lg-12">
                        <div class="text-center"><h1 class="hero-header">Other Devices</h1></div>
                    </div>
                </div>
                <div class="row mt-50 pb-50">
                    <div class="col-lg-1"></div>
                    @if(isset($rowtri))
                        @foreach($rowtri as $row)
                        <div class="col-lg-2 col-md-4 device-link" style="align-items: center; display: flex;">
                            <a href="{{ url('products/brand', $row->name) }}" class="hvr-shrink">
                                <img src="{{ url($row->photo) }}" class="img-fluid" />
                                <h4 class="device-name">{{ $row->name }}</h4>
                            </a>
                        </div>
                        @endforeach
                    @endif
                    <div class="col-lg-1"></div>
                </div>
                <div class="row mt-50 pb-50">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <h2 class="text-white">Choose How You Wish to be Paid</h2>
                            <h5 style="color: #7c7c7c; font-weight:normal">We cater to a vast amount of payment provider</h5>
                        </div>
                    </div>
                </div>
                <div class="row pb-50">
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/1.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/2.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/3.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/4.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/5.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="text-center">
                            <img src="{{ url('assets/images/payments/6.png') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-grey">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 col-md-6 parallax-1" id="iviglp"></div>
                    <div class="col-lg-4 col-md-6 right-content">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title ct-tronics">SELLING YOUR PHONE.</h5>
                                <hr/>
                                <h4 class="sh-tronics">How It Works</h4>
                                <p class="card-text content-p pb-30">
                                    <strong>Step 1 - Get an Instant Offer.</strong> Tell us a little bit about your phone and we’ll make an offer right away! We have a highest price guarantee &amp; there are no obligations.
                                </p>
                                <p class="card-text content-p pb-30">
                                    <strong>Step 2 - Ship Your Phone.</strong> Shipping is 100% free. We’ll send you a prepaid shipping label via email. Simply print the label and place it on a box or padded envelope.
                                </p>
                                <p class="card-text content-p pb-30">
                                    <strong>Step 3 - Get Paid.</strong> Once we receive your order, it’s time for you to cash in! Tronics Pay offers the highest payouts, 30-day price locks, and speedy payments.
                                </p>
                                <p class="card-text content-p">
                                    <strong>Speedy Payment Options.</strong> Choose the payment method that works best for you. We can send you a check in the mail or send your payment directly to your PayPal account.
                                </p>
                                <div class="more-details">
                                    <a href="{{ url('how-it-works') }}" class="hvr-shrink">More Details 
                                        <i class="fas fa-long-arrow-alt-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-grey">
            <div class="container-fluid parallax-2">
                <div class="row" style="padding: 50px 0px;">
                    <div class="col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <div data-aos="fade-right" data-aos-duration="500" class="card-step">
                                    <h3>Step 1</h3>
                                    <p>INPUT DEVICE INFO</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <div data-aos="fade-right" data-aos-duration="1500" class="card-step">
                                    <h3>Step 2</h3>
                                    <p>GET SHIPPING LABEL</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <div data-aos="fade-right" data-aos-duration="2000" class="card-step">
                                    <h3>Step 3</h3>
                                    <p>GET PAID!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-grey">
            <div class="container pb-50">
                <div class="text-center hero-header pt-50">
                    <h1 class="text-black">What Others Have To Say About Us...</h1>
                    <p style="padding-top: 20px;"><img src="assets/images/reviews/1.png" class="img-fluid" /></p>
                </div>
                <div class="row">
                    <div class="col-lg-6"><img src="assets/images/reviews/2.png" class="img-fluid" /></div>
                    <div class="col-lg-6"><img src="assets/images/reviews/3.png" class="img-fluid" /></div>
                </div>
                <div class="row">
                    <div class="col-lg-6"><img src="assets/images/reviews/4.png" class="img-fluid" /></div>
                    <div class="col-lg-6"><img src="assets/images/reviews/5.png" class="img-fluid" /></div>
                </div>
                <div class="row">
                    <div class="col-lg-6"><img src="assets/images/reviews/6.png" class="img-fluid" /></div>
                    <div class="col-lg-6"><img src="assets/images/reviews/7.png" class="img-fluid" /></div>
                </div>
            </div>
        </section>
        <section class="section-grey">
            <div class="container pb-50">
                <div class="row">
                    <div class="col-lg-5" style="background-color: rgb(255, 255, 255);">
                        <div style="padding: 50px;">
                            <img src="assets/images/reviews/8.png" class="img-fluid" /><img src="assets/images/reviews/9.png" class="img-fluid" /><img src="assets/images/reviews/10.png" class="img-fluid" />
                            <img src="assets/images/reviews/11.png" class="img-fluid" />
                        </div>
                    </div>
                    <div class="col-lg-7" style="background-color: rgb(238, 238, 233);">
                        <div class="text-center contact-us">
                            <i class="far fa-fw fa-envelope"></i>
                            <h4 class="contact-head">Contact Us</h4>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3097.2195538694464!2d-94.41648289308372!3d39.07869647373669!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87c0fdf2628895ff%3A0x74b538aa176b05bc!2sTronics%20Pay!5e0!3m2!1sen!2sph!4v1605701145623!5m2!1sen!2sph" width="100%" height="450" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false" tabindex="0" id="ij70oa"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>



@endsection