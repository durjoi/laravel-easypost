![plot](./public/assets/images/logo.png)

## Instruction

Change Email sending from `MailTrap` to `MailGun`

In `ApiController` change the recipeient of the email notification

---

## Updating File

Follow instruction below for installation of app

```Package
execute >  `composer update`
```

```DATABASE
execute >  `php artisan migrate:fresh`
```

```Seeder
execute >  `php artisan db:seed`
```

---

## Instruction

Follow instruction below for installation of app

## For Server

Configure file to adjust the maximum allowed packet accepted in server

```SERVER
my.ini
```

and set

```SERVER
max_allowed_packet
```

to

```SERVER
max_allowed_packet = 200M
```

## For Database

Update composer for any further updates on vendor

```DATABASE
execute >  `composer update`
```

Run migrate to migrate additional alters in tables and to re-run migration

```DATABASE
execute > `php artisan migrate:fresh`
```

Run seeder to insert data in database

```DATABASE
execute > `php artisan db:seed`
```

Open `page_builder.sql` in the root directory of TronicsPay app

Open `PHPMyAdmin` and import `page_builder.sql` and `run`

---

## For Login Authentication

Use Administrator credential to access backend page

```CREDENTIALS
email: admin@tronicspay.com
password: 123456
```

```
Added develop branch
```

---

## For Paypal Integration

Create Paypal account

Get API Credentials from Paypal

Go to TronicsPay Root

Go to `app\Models` and open `TableList.php`

Search for `$paypal_account` and insert your paypal credentials
