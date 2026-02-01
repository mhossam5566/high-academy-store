@extends('user.layouts.master')

@section('title')
    تسجيل الدخول
@endsection

@section('content')
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        }

        .login-side p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .register-btn {
            background: white;
            color: #667eea;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: #4f46e5;
        }

        .login-form-side {
            padding: 3rem;
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
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
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
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
        }

        .form-check-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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
            background: var(--border-color);
        }

        .divider span {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .error-messages {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-messages ul {
            margin: 0;
            padding-right: 1.25rem;
            color: #dc2626;
            font-size: 0.9rem;
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
                <!-- Right Side - Welcome Message -->
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

                <!-- Left Side - Login Form -->
                <div class="login-form-side">
                    <div class="login-header">
                        <h3>تسجيل الدخول</h3>
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
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                id="email"
                                placeholder="example@email.com"
                                value="{{ old('email') }}"
                                required 
                            />
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                id="password"
                                placeholder="••••••••"
                                required 
                            />
                        </div>

                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="remember" 
                                    id="remember"
                                    checked 
                                />
                                <label class="form-check-label" for="remember">
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