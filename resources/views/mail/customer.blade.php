<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TronicsPay</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <!-- <link rel="stylesheet" href="{{ url('/library/plugins/fontawesome/css/font-awesome.css') }}"> -->
        <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
        
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ url('css/admin-style.css') }}">
        <style>
            body {
                background: #e4e4e4;
            }
            body, td {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }
            .bordered {
                border: 1px solid #000;
            }
            .table:not(.table-dark) {
                color: inherit;
            }
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                background-color: transparent;
            }
            table {
                border-collapse: collapse;
            }
            table {
                // border-collapse: separate;
                text-indent: initial;
                border-spacing: 2px;
            }
            thead {
                display: table-header-group;
                vertical-align: middle;
                border-color: inherit;
            }
            .table th, .table td {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }
            .valign-top {
                vertical-align: top;
            }
            .label {
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                display: inline;
                padding: .2em .6em .3em;
                font-size: 75%;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: .25em;
            }
            .label-default {
                background-color: #777;
            }
            .label-primary {
                background-color: #337ab7;
            }
            .label-success {
                background-color: #5cb85c;
            }
            .label-info {
                background-color: #5bc0de;
            }
            .label-warning {
                background-color: #f0ad4e;
            }
            .label-danger {
                background-color: #d9534f;
            }
            .pad10rem {
                padding: .10rem;
            }
            .lead {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 1.25rem;
                font-weight: 300;
            }
            img { display:block }
        </style>
    </head>
    <body>
        <div class="content" style="background: #e4e4e4;">
            <div class="row">
                <div class="col-md-12">
                    <div style="padding: 50px;">
                        <div style="border: 1px solid #777; background: #fff; padding: 15px;">
                            <div class="row">
                                <div class="form-group col-md-12" align="center">
                                    <img src="{{ url('./assets/images/logo.png')}}">
                                </div>
                            </div>
                            <br />
                            <br />
                            <div class="row">
                                <div class="form-group col-md-12" style="font-size: 20px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <tbody>
                                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                          {!! $header !!}
                                        </td>
                                      </tr>
                                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                          <p>
                                            Howdy {{ $fname }},<br><br>
                                            Thank you for selling a device {{ $model }} to us. We currently reviewing your application and we will get back to you as soon as possible.<br><br>
                                            We also created an account for you, you can login at <a href="https://tronicspay.com/customer/auth/login" target="_blank">Member Login</a> using these email {{ $email }} with the password <b>{{ $password }}</b>.
                                            If you have any further questions please email as at {{ $company_email }}.
                                          </p>
                                        </td>
                                      </tr>
                                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                          <p>Regards,<br><b>TronicsPay</b></p>
                                        </td>
                                      </tr>
                                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                          &copy; <?php echo date('Y'); ?> TronicsPay
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
</html>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TronicsPay Email Confirmation</title>
<style>
body{margin-top:20px;}
</style>
</head>
<body>
<table class="body-wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
  <tbody>
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
      <td style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
      <td
        class="container"
        width="600"
        style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
        valign="top"
      >
        <div class="content" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
          <table
            class="main"
            width="100%"
            cellpadding="0"
            cellspacing="0"
            itemprop="action"
            itemscope=""
            itemtype="http://schema.org/ConfirmAction"
            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;"
          >
            <tbody>
              <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td
                  class="content-wrap"
                  style="
                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    box-sizing: border-box;
                    font-size: 14px;
                    vertical-align: top;
                    margin: 0;
                    padding: 30px;
                    border: 3px solid #d39e00;
                    border-radius: 7px;
                    background-color: #fff;
                  "
                  valign="top"
                >
                  <meta itemprop="name" content="Confirm Email" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                  <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <tbody>
                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                          {!! $header !!}
                        </td>
                      </tr>
                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                          <p>
                            Howdy {{ $fname }},<br><br>
                            Thank you for selling a device {{ $model }} to us. We currently reviewing your application and we will get back to you as soon as possible.<br><br>
                            We also created an account for you, you can login at <a href="https://tronicspay.com/customer/auth/login" target="_blank">Member Login</a> using these email {{ $email }} with the password <b>{{ $password }}</b>.
                            If you have any further questions please email as at {{ $company_email }}.
                          </p>
                        </td>
                      </tr>
                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td class="content-block" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                          <p>Regards,<br><b>TronicsPay</b></p>
                        </td>
                      </tr>
                      <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <td class="content-block" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                          &copy; <?php echo date('Y'); ?> TronicsPay
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html> -->