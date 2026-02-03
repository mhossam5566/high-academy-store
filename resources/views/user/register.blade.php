@extends('user.layouts.master')

@section('title')
    إنشاء حساب جديد
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

        .register-container {
            margin-top: 60px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fff5eb 0%, #ffffff 100%);
            padding: 2rem 0;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(232, 146, 56, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 90%;
        }

        .register-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .register-side {
            background: linear-gradient(135deg, #f8c291 0%, #e8a87c 50%, #d4a574 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .register-side h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .register-side p {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .login-btn {
            background: white;
            color: #e89238;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            color: #d67e1f;
            background: #fffbf7;
        }

        .register-form-side {
            padding: 3rem;
            background: white;
            max-height: 90vh;
            overflow-y: auto;
            direction: rtl;
            text-align: right;
        }

        .register-form-side::-webkit-scrollbar {
            width: 6px;
        }

        .register-form-side::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .register-form-side::-webkit-scrollbar-thumb {
            background: #e89238;
            border-radius: 10px;
        }

        .register-header {
            margin-bottom: 2rem;
        }

        .register-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
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

        .text-danger {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .register-form-side .invalid-feedback,
        .register-form-side .text-danger,
        .register-form-side .error-messages,
        .register-form-side .alert {
            direction: rtl;
            text-align: right;
        }

        .submit-btn {
            background: linear-gradient(135deg, #f5a957 0%, #e89238 100%);
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
            box-shadow: 0 10px 25px rgba(212, 165, 116, 0.3);
            background: linear-gradient(135deg, #e8a87c 0%, #d4a574 100%);
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
            background: rgba(255, 255, 255, 0.3);
        }

        .divider span {
            color: white;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .register-content {
                grid-template-columns: 1fr;
            }

            .register-side {
                padding: 2rem;
            }

            .register-form-side {
                padding: 2rem;
                max-height: none;
            }

            .register-side h2 {
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

        .register-card {
            animation: fadeInUp 0.6s ease;
        }
    </style>

    <div class="register-container">
        <div class="register-card">
            <div class="register-content">
                <div class="register-side">
                    <div>
                        <h2>انضم إلينا الآن!</h2>
                        <p>أنشئ حسابك الجديد وابدأ رحلتك معنا. نحن متحمسون لوجودك في مجتمعنا</p>
                        <div class="divider">
                            <span>لديك حساب بالفعل؟</span>
                        </div>
                        <a href="{{ route('user.login.user') }}" class="login-btn">
                            تسجيل الدخول
                        </a>
                    </div>
                </div>

                <div class="register-form-side" dir="rtl">
                    <div class="register-header">
                        <h3>إنشاء حساب جديد</h3>
                        <p>املأ البيانات التالية لإنشاء حسابك</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('user.register.submit') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="أدخل اسمك الكامل"
                                value="{{ old('name') }}" />

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="example@email.com"
                                value="{{ old('email') }}" />

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" />

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="••••••••" />
                        </div>

                        <button type="submit" class="submit-btn">
                            إنشاء الحساب
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
