<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablelist extends Model
{

    public $paypal_admin = [
                    'sandbox_account' => 'sb-jcj7x5459425@business.example.com', 
                    'client_id' => 'ATchzVJN5IS1NvWOCkOwXTIj58wFXPqCT1GVHh1yRmE1NSrKREdIO4UcBMWzCSGav-Hmry9e-twxcE6q',
                    'secret' => 'EO6wqntzqUb5aajIv1l4xNWmJ0p5ZLbLrJJB9E3clGjfbJUEL485ks9f_g2vQjk4VYInnO5e7sJSMfQJ'
    ];

    // Sandbox credentials
    public $paypal_account = [
        'paypal_account_client_id' => 'ATchzVJN5IS1NvWOCkOwXTIj58wFXPqCT1GVHh1yRmE1NSrKREdIO4UcBMWzCSGav-Hmry9e-twxcE6q',
        'paypal_account_username' => 'sb-g47lww5767188@personal.example.com', 
        'paypal_account_password' => 'qF_7uZ0#'
    ];

    public $aws_account = [ 
        'Account ID' => '144507505489', 
        'URL' => 'https://144507505489.signin.aws.amazon.com/console', 
        'User name' => 'glennabalos', 
        'Password' => '_Mjm=&AyrK&N,NWP,*SP'
    ];

    public $badge = [
        'label-sucess', 'label-info', 'label-primary', 'label-warning', 'label-danger'
    ];

    // public $paypal_account = [
    //     'paypal_account_client_id' => '',
    //     'paypal_account_username' => '', 
    //     'paypal_account_password' => ''
    // ];

    public $email_support = [
                                'email' => 'support@lawsheroes.com',
                                'password' => 'rpg46jWa@tL('
                            ];
    
    public $networkList = ['AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked','Others'=>'Others'];

    public $storageList = [''=>'--', '32GB'=>'32GB', '64GB'=>'64GB', '128GB'=>'128GB', '256GB'=>'256GB', '512GB'=>'512GB'];

    public $smtp = [
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.mailtrap.io',
        'MAIL_PORT' => '2525',
        'MAIL_USERNAME' => '399502d79e0d03',
        'MAIL_PASSWORD' => 'c228e80f9f498e',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_FROM_ADDRESS' => 'aen00100@gmail.com',
        'MAIL_FROM_NAME' => 'TronicsPay',
    ];

	/**************************************************************/
	/** 		https://restfulapi.net/http-status-codes/		**/
	/**************************************************************/

    protected $api_response = [
	    						'200' => 'OK',
	    						'201' => 'Created',
	    						'202' => 'Accepted',
	    						'204' => 'No content',
	    						'301' => 'Moved Permanently',
	    						'302' => 'Found',
	    						'303' => 'See Others',
	    						'304' => 'Not Modified',
	    						'307' => 'Temporary Redirect',
	    						'400' => 'Bad Request',
	    						'401' => 'Unauthorized',
	    						'403' => 'Forbidden',
	    						'404' => 'Not Found',
	    						'405' => 'Method Not Allowed',
	    						'406' => 'Not Acceptable',
	    						'412' => 'Precondition Failed',
	    						'415' => 'Unsupported Media Type',
	    						'500' => 'Internal Server Error',
	    						'501' => 'Not Implemented'
    						];

    protected $custom_api_response = [
    							'1001' => 'Email Exists', 
    							'1002' => 'Value too long', 
    							'1003' => 'Value too short',
    							'1004' => 'string only',
    							'1005' => 'Email not registered', 
    							'1006' => 'Password and password confirmation did not match', 
    							'1007' => 'Incorrect Password', 
                                '1008' => 'Incorrect file format', 
                                '1009' => 'Account suspended', 
                                '1010' => 'Record In-Used', 
    							'2001' => 'Email Address is required',
    							'2002' => 'Mobile no. is required',
    							'2003' => 'Password is required',
                                '3001' => 'Successfully registered',
                                '3001' => 'Successfully registered',
                                '3100' => 'Profile has been successfully updated',
                                '3101' => 'Profile avatar changed',
                                '3102' => 'Profile password changed', 
                                '4000' => 'Added',
                                '4001' => 'Updated',
                                '4002' => 'Deleted',
                                '5000' => 'Payment Success',
                                '5001' => 'Payment Canceled',
                                '5002' => 'Payment Rejected'
    						];

	public $valid_file_extensions = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];  
      
    public $store_details = ['store_id' => '20018', 'auth_key' => 'TXpKG#6zkdB~2zTK'];		


    public $inquiry_status = [
                                0 => 'Open',
                                1 => 'Assigned',
                                2 => 'Assigned to others',
                                3 => 'Unpaid',
                                4 => 'Paid',
                            ];

    public $fcm_api_accesskey = 'AAAAUKR6L4M:APA91bGwwSuo_8hsrdfAT5Zatg95fpLevgFZrJQvJoqTZSFfs6dFP6m7uljuG283h5B-2UWYH2d94PbiLuLdb0P8KabJP17IuIZ38SqkF1Pm8pHzvWZ30WEYRjMmGnLZSWZBOGpOiE2U';

    public $modulesList = [
        "Brand", "Config", "Customer", "Dashboard", "Menu", "Order", "Page", "Product", "Status", "User"
    ];

    public $enableOption = ["Disable", "Enable"];

    public $modules = [
                        "dashboard" => ["id" => 1, "name" => "Dashboard", "icon" => "nav-icon fas fa-tachometer-alt", "modal" => ""],
                        "order" => ["id" => 2, "name" => "Orders", "icon" => "nav-icon fas fa-people-carry", "modal" => ""],
                        "customer" => ["id" => 2, "name" => "Customers", "icon" => "nav-icon fas fa-users", "modal" => ""],
                        "page" => ["id" => 3, "name" => "Pages", "icon" => "nav-icon fas fa-file", "modal" => "modal-show-advice"],
                        "product" => ["id" => 4, "name" => "Products", "icon" => "nav-icon fas fa-shopping-basket", "modal" => ""],
                        "user" => ["id" => 5, "name" => "Users", "icon" => "nav-icon fas fa-user-friends", "modal" => ""],
                        "config" => ["id" => 6, "name" => "Configuration", "icon" => "far fa-circle nav-icon", "modal" => ""],
                        "menu" => ["id" => 7, "name" => "Menu Manager", "icon" => "far fa-circle nav-icon", "modal" => ""],
                        "brand" => ["id" => 8, "name" => "Brands Manager", "icon" => "far fa-circle nav-icon", "modal" => ""],
                        "status" => ["id" => 9, "name" => "Status Manager", "icon" => "far fa-circle nav-icon", "modal" => ""]
                    ];
}