<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .container {
            width: 100%;
            max-width: 448px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 32px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h2 {
            font-size: 30px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        /* Error Messages */
        .error-box {
            margin-bottom: 24px;
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: 16px;
        }

        .error-content {
            display: flex;
            align-items: flex-start;
        }

        .error-icon {
            width: 20px;
            height: 20px;
            color: #ef4444;
            margin-right: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .error-list {
            list-style: none;
            padding: 0;
        }

        .error-list li {
            font-size: 14px;
            color: #b91c1c;
            margin-bottom: 4px;
        }

        .error-list li:last-child {
            margin-bottom: 0;
        }

        /* Form */
        form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.15s;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: white;
            font-weight: 600;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.15s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            transform: scale(1.02);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .btn-submit:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Footer */
        .footer {
            margin-top: 24px;
            text-align: center;
        }

        .footer p {
            font-size: 14px;
            color: #6b7280;
        }

        .footer a {
            font-weight: 500;
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.15s;
        }

        .footer a:hover {
            color: #2563eb;
        }

        /* Copyright */
        .copyright {
            text-align: center;
            margin-top: 24px;
        }

        .copyright p {
            font-size: 14px;
            color: #6b7280;
        }

        @media (max-width: 640px) {
            .card {
                padding: 24px;
            }

            .header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Card -->
        <div class="card">
            <!-- Header -->
            <div class="header">
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-box">
                    <div class="error-content">
                        <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            class="form-input"
                            placeholder="you@example.com"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            required
                            class="form-input"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="btn-submit">
                        Sign In
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="footer">
                <p>
                    Don't have an account? 
                    <a href="#">Contact Admin</a>
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            <p>© 2026 Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>