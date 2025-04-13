# Floofment

Floofment is a web application for managing and sharing memories, with features for phone recordings, shared memories, and user dashboard functionality. The name is a play on "elopement" and my sister's term of endearment - "floof". This application was rapidly prototyped over the span of a few days.

## Features

- Receive voicemail with a custom audio greeting via Twilio
- Receive memories as photos, voice messages, and text
- Alias phone numbers for viewing in the dashboard
- Listen to voicemail, voice messages, in the dashboard

![Screenshot of the homepage for leaving a memory](/docs/leave-memory.png)

![Screenshot of the voicemail viewing interface](/docs/view-voicemail.png)

## What this project doesn't do

This is not setup to be multi-tenant or separate the input. A user can access everything, and vice-versa. This project also is lacking some basic functionality like deleting voice messages, etc., due to those not being in demand at the time of creation.

## Installation

This project is built with Laravel. For basic installation instructions, please follow the [official Laravel documentation](https://laravel.com/docs/12.x/installation). This repository is not designed for ease of use and some basic Laravel knowledge to get up and running is expected.

This project does make use of [Laravel Reverb](https://laravel.com/docs/12.x/reverb#main-content), so some insight there may be necessary. You can refer to `floofment-le-ssl.conf` in the root of this repo for a basic Apache config.

### Quick Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Make any changes to your `.env` to suit your setup, refer to Environment Configuration below
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Build assets:
   ```bash
   php artisan serve
   ```
8. Install systemd unit files - refer to the systemd folder, only `floofment-reverb.service` is required. Make any changes necessary, such as to the path, and ensure reverb is up and running.

### Creating a user

You can run the following to create user:

```bash
php artisan app:create-user
```

### Development

For local development, you can use:

```bash
composer dev
```

This runs the application server, queue worker, log viewer, reverb server, and Vite development server concurrently.

### Environment Configuration

While most everything is boilerplate Laravel, there are a few notable environment values you should set.

#### Twilio

Twilio is a requirement for the voicemail portion of this project to work.

- `TWILIO_SID` & `TWILIO_TOKEN`
  Your Twilio credentials.
- `TWILIO_GREETING`
  Path, relative to `/public`, that you have placed your audio greeting file. I recommend placing it in the `audio` folder, which is ignored by git by default.

##### Twilio Console Configuration

For any numbers you want to receive voicemail on, ensure you set the inbound webhook as `https://yourdomain.com/webhook/twilio/inbound`.

#### Cloudflare Turnstile

This project makes use of Cloudflare turnstile for it's memory sharing frontend. You'll want to follow their instructions for obtaining a site key and secret key and set the `CF_TURNSTILE_SITE_KEY` and `CF_TURNSTILE_SECRET_KEY` environment variables.
