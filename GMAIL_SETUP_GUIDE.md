# Setting Up Gmail for Your NovaEra HMS Contact Form

To make your contact form emails actually reach your Gmail inbox, you need to complete a few more steps. Gmail has security measures that prevent applications from sending emails using your account unless you explicitly allow it.

## Step 1: Create an App Password (Recommended Method)

For security reasons, Google requires you to use an "App Password" instead of your regular Gmail password:

1. Make sure 2-Step Verification is enabled on your Google Account:

    - Go to your [Google Account](https://myaccount.google.com/)
    - Select "Security" from the left menu
    - Under "Signing in to Google", select "2-Step Verification" and follow the steps

2. Create an App Password:

    - Go to [App passwords](https://myaccount.google.com/apppasswords)
    - Select "Mail" as the app and "Other" as the device type
    - Enter "NovaEraHMS" as the name
    - Click "Generate"
    - Google will display a 16-character password - **copy this password**

3. Update your `.env` file:
    - Paste the 16-character app password as the value for `MAIL_PASSWORD` in your `.env` file
    - Make sure your username is correct: `MAIL_USERNAME=edingrazhda17@gmail.com`

## Step 2: Allow Less Secure Apps (Alternative, Less Secure Method)

If the App Password approach doesn't work, you can try allowing less secure apps to access your Gmail account (not recommended for security reasons):

1. Go to your [Google Account](https://myaccount.google.com/)
2. Select "Security" from the left menu
3. Scroll down to "Less secure app access" and turn it on

## Step 3: Test Your Contact Form

After setting up your Gmail account:

1. Restart your Laravel application (if running on a server)
2. Submit a test message through your contact form
3. Check your Gmail inbox (and spam/junk folders) for the message

## Troubleshooting

If emails still don't arrive:

1. Check your Laravel logs for errors: `storage/logs/laravel.log`
2. Make sure your `MAIL_PASSWORD` doesn't contain any quotes or spaces
3. Try using environment variables without quotes: `MAIL_PASSWORD=yourpassword` (no quotes)
4. Check if your hosting provider allows outgoing SMTP connections on port 587
5. If using a local development environment, make sure your firewall isn't blocking outgoing connections

## For Development Purposes

If you're just testing the application during development, you can use Laravel's built-in email preview features:

1. Set `MAIL_MAILER=log` in your `.env` file
2. Emails will be written to your Laravel log file instead of being sent
3. Check `storage/logs/laravel.log` to see the email content
