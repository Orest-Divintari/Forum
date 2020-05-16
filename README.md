#Insomnia

This is a tech forum

##Installation

### Prerequisites

-   To run this project, you must have PHP 7 installed.
-   You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet.
-   If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart).

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone
https://github.com/Orest-Divintari/Insomnia.git
cd insomnia && composer install && npm install
mv .env.example .env
php artisan key:generate
npm run dev
```

### Step 2

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example bbelow, we've named the database, "forum"

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3

reCAPTCHA is a Google tool to help prevent forum spam. You'll need to create a free account.

https:://google.com/recaptcha/intro

Choose reCAPTCHA V2, and specify your local (and eventually product) domain name.

Once sumbitted, you';; see two improtant keys that should be reference in your .env file

```
RECAPTCHA_SITE_KEY=PASTE_KEY_HERE
RECAPTCHA_SECRET=PASTE_SECRET_HERE
```

### Step 4

Until an administration portal is available, manually insert any number of "channels" ( think of these as forum categories) into the "channels" table in your database.
Once finished, clear your serve cache, and you're all set go go!

```
php artisan cache:clear
```

### Step 5

Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to `http://insomnia.test`.

1. Visit: `http://insomnia.test/register` to register a new forum account.
2. Edit `config/insomnia.php`, and add any email address that should be marked as an administrator.
3. Visit: `http://insomnia.test/admin/channels` to seed your forum with one or more channels.
