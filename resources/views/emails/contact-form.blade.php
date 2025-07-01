<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #F8B803;
            color: #1B1B18;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 0 15px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NovaEra HMS Contact Form Submission</h1>
        </div>
        
        <div class="content">
            <div class="field">
                <div class="label">Name:</div>
                <div>{{ $data['name'] }}</div>
            </div>
            
            <div class="field">
                <div class="label">Email:</div>
                <div>{{ $data['email'] }}</div>
            </div>
            
            <div class="field">
                <div class="label">Subject:</div>
                <div>{{ $data['subject'] }}</div>
            </div>
            
            <div class="field">
                <div class="label">Message:</div>
                <div>{{ $data['message'] }}</div>
            </div>
        </div>
        
        <div class="footer">
            This email was sent from the contact form on the NovaEra HMS website.
        </div>
    </div>
</body>
</html>
