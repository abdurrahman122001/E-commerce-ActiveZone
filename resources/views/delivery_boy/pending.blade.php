<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ translate('Account Pending Approval') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aiz-core.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .pending-card {
            max-width: 500px;
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .pending-icon {
            font-size: 64px;
            color: #ffc107;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="pending-card">
        <div class="pending-icon">
            <i class="las la-clock"></i>
        </div>
        <h2 class="h4 mb-3">{{ translate('Account Pending Approval') }}</h2>
        <p class="text-muted">
            {{ translate('Your delivery boy account has been created successfully but it is currently waiting for administrator approval.') }}
        </p>
        <p class="text-muted">
            {{ translate('Please check back later or contact support if you have any questions.') }}
        </p>
        <div class="mt-4">
            <a href="{{ route('logout') }}" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ translate('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</body>
</html>
