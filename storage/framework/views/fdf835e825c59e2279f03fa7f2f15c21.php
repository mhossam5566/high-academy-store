<?php $__env->startSection('title'); ?>
    الأسئلة الشائعة
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .page-title {
            text-align: center;
            margin: 80px 0;
            padding: 0 20px;
        }

        .page-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .page-title h1:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .faq-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 30px;
            direction: rtl;
        }

        .faq-card {
            margin-top: 10px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .faq-card:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .faq-card:hover:before {
            transform: scaleX(1);
        }

        .faq-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .faq-number {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .faq-card:hover .faq-number {
            transform: rotate(360deg) scale(1.1);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .faq-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .faq-icon i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .faq-question {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            line-height: 1.6;
            padding-left: 60px;
        }

        .faq-answer {
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.8;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            padding: 20px;
            border-radius: 12px;
            border-right: 4px solid #667eea;
            position: relative;
        }

        .faq-answer:before {
            content: '\f10d';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            color: rgba(102, 126, 234, 0.15);
        }

        .answer-content {
            max-height: 3.6em;
            /* تقريباً سطرين */
            overflow: hidden;
            transition: max-height 0.5s ease;
            position: relative;
        }

        .answer-content.expanded {
            max-height: none;
        }

        .read-more-btn {
            display: inline-block;
            margin-top: 12px;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            border: 2px solid #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        .read-more-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .read-more-btn i {
            margin-right: 5px;
            transition: transform 0.3s ease;
        }

        .read-more-btn.expanded i {
            transform: rotate(180deg);
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: white;
        }

        .empty-state h4 {
            color: #2d3748;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #718096;
            font-size: 1.1rem;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
            }
        }

        @media (max-width: 992px) {
            .faq-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-title h1 {
                font-size: 2rem;
            }

            .faq-card {
                padding: 25px 20px;
            }

            .faq-question {
                font-size: 1.1rem;
                padding-left: 0;
            }

            .faq-number {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
        }
    </style>

    <div class="page-title">
        <h1>الأسئلة الشائعة</h1>
    </div>

    <div class="container-fluid">
        <div class="faq-container">
            <?php $__empty_1 = true; $__currentLoopData = \App\Models\Faq::active()->ordered()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if($index == 0 || $index % 2 == 0): ?>
                    <div class="faq-grid">
                <?php endif; ?>

                <div class="faq-card">
                    <div class="faq-number"><?php echo e($index + 1); ?></div>

                    <div class="faq-icon">
                        <i class="fas fa-question"></i>
                    </div>

                    <div class="faq-question">
                        <?php echo e($faq->question); ?>

                    </div>

                    <div class="faq-answer">
                        <div class="answer-content" id="answer-<?php echo e($index); ?>">
                            <?php echo nl2br(e($faq->answer)); ?>

                        </div>
                        <span class="read-more-btn" onclick="toggleAnswer(<?php echo e($index); ?>)">
                            <i class="fas fa-chevron-down"></i>
                            <span class="read-more-text">قراءة المزيد</span>
                        </span>
                    </div>
                </div>

                <?php if($index == count(\App\Models\Faq::active()->ordered()->get()) - 1 || $index % 2 == 1): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h4>لا توجد أسئلة شائعة حالياً</h4>
            <p>سيتم إضافة الأسئلة الشائعة قريباً. تابعنا للمزيد من المعلومات</p>
        </div>
        <?php endif; ?>
    </div>
    </div>

    <script>
        function toggleAnswer(index) {
            const answerContent = document.getElementById('answer-' + index);
            const button = event.currentTarget;
            const icon = button.querySelector('i');
            const text = button.querySelector('.read-more-text');

            if (answerContent.classList.contains('expanded')) {
                answerContent.classList.remove('expanded');
                button.classList.remove('expanded');
                text.textContent = 'قراءة المزيد';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                answerContent.classList.add('expanded');
                button.classList.add('expanded');
                text.textContent = 'قراءة أقل';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/user/fqa.blade.php ENDPATH**/ ?>