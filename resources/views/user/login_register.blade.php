@extends('user.layouts.master')

@section('title')
    تسجيل الدخول
@endsection

@section('content')
    <style>
        :root {
            --primary-color: #e89238;
            --primary-hover: #d67e1f;
            --primary-light: #f5a957;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --bg-light: #fafafa;
        }

        .login-container {
            margin-top: 60px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fff5eb 0%, #ffffff 100%);
            padding: 2rem 0;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(232, 146, 56, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 90%;
        }

        .login-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .login-side {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 50%, #a0aec0 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .login-side h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2d3748;
        }

        .login-side p {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            line-height: 1.6;
            color: #4a5568;
        }

        .register-btn {
            background: #4a5568;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            color: white;
            background: #2d3748;
        }

        .login-form-side {
            padding: 3rem;
            background: white;
            direction: rtl;
            text-align: right;
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            background: #fafafa;
            direction: rtl;
            text-align: right;
        }

        .form-control:focus {
            border-color: #e89238;
            box-shadow: 0 0 0 3px rgba(232, 146, 56, 0.1);
            outline: none;
            background: white;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #e89238;
        }

        .form-check-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-password {
            color: #e89238;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #d67e1f;
            text-decoration: underline;
        }

        .submit-btn {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(74, 85, 104, 0.3);
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
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
            background: rgba(45, 55, 72, 0.3);
        }

        .divider span {
            color: #4a5568;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .error-messages {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-messages ul {
            margin: 0;
            padding-right: 1.25rem;
            color: #c53030;
            font-size: 0.9rem;
        }

        .login-form-side .invalid-feedback,
        .login-form-side .error-messages,
        .login-form-side .alert {
            direction: rtl;
            text-align: right;
        }

        @media (max-width: 768px) {
            .login-content {
                grid-template-columns: 1fr;
            }

            .login-side {
                padding: 2rem;
            }

            .login-form-side {
                padding: 2rem;
            }

            .login-side h2 {
                font-size: 1.5rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.6s ease;
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <div class="login-content">
                <div class="login-side">
                    <div>
                        <h2>مرحباً بعودتك!</h2>
                        <p>نحن سعداء برؤيتك مرة أخرى. قم بتسجيل الدخول للوصول إلى حسابك والاستمتاع بخدماتنا</p>
                        <div class="divider">
                            <span>ليس لديك حساب؟</span>
                        </div>
                        <a href="{{ route('user.register.user') }}" class="register-btn">
                            إنشاء حساب جديد
                        </a>
                    </div>
                </div>

                <div class="login-form-side">
                    <div class="login-header">
                        <h3>تسجيل الدخول</h3>
                        <p>أدخل بياناتك للوصول إلى حسابك</p>
                    </div>

                    @if ($errors->has('general'))
                        <div class="error-messages">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    <form action="{{ route('user.login.submit') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="example@email.com"
                                value="{{ old('email') }}" required />

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                required />

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked />
                                <label class="form-check-label" style='margin-left: 0.5rem;' for="remember">
                                    تذكرني
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        <button type="submit" class="submit-btn">
                            تسجيل الدخول
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
