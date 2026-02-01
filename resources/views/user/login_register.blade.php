@extends('user.layouts.master')

@section('title')
    تسجيل الدخول
@endsection

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            padding: 2rem 1rem;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            max-width: 450px;
            width: 100%;
            padding: 2.5rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #cbd5e0;
            background: white;
            box-shadow: 0 0 0 3px rgba(203, 213, 224, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .form-check-label {
            color: #4a5568;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-link {
            color: #4a5568;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #2d3748;
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: #2d3748;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: #1a202c;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            color: #a0aec0;
            font-size: 0.85rem;
        }

        .register-section {
            text-align: center;
        }

        .register-section p {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .register-link {
            display: inline-block;
            color: #2d3748;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .error-messages {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-messages ul {
            margin: 0;
            padding-right: 1.25rem;
            color: #c53030;
            font-size: 0.9rem;
            list-style: arabic-indic;
        }

        .error-messages li {
            margin-bottom: 0.25rem;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 2rem 1.5rem;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h2>تسجيل الدخول</h2>
                <p>أدخل بياناتك للوصول إلى حسابك</p>
            </div>

            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.login.submit') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="example@email.com"
                        value="{{ old('email') }}" required />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="••••••••"
                        required />
                </div>

                <div class="form-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked />
                        <label class="form-check-label" for="remember">
                            تذكرني
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        نسيت كلمة المرور؟
                    </a>
                </div>

                <button type="submit" class="submit-btn">
                    تسجيل الدخول
                </button>
            </form>

            <div class="divider">
                <span>أو</span>
            </div>

            <div class="register-section">
                <p>ليس لديك حساب؟</p>
                <a href="{{ route('user.register.user') }}" class="register-link">
                    إنشاء حساب جديد
                </a>
            </div>
        </div>
    </div>
@endsection
