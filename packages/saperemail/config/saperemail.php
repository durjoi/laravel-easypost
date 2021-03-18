<?php

return [
  'from_name'       => env('MAIL_FROM_NAME'),
  'from_email'      => env('MAIL_FROM_ADDRESS'),
  'host'            => env('MAIL_HOST'),
  'username'        => env('MAIL_USERNAME'),
  'password'        => env('MAIL_PASSWORD'),
  'port'            => env('MAIL_PORT', 587),
  'auth'            => env('MAILER_AUTH', true)
];