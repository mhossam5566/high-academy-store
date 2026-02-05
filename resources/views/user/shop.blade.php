@extends('user.layouts.master')
@section('title')
    High Academy Store - المتجر
@endsection

@section('content')
    <style>
        /* Search & Filter Section */
        .search-filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }
        
        .search-filter-section.books-theme {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .search-filter-section.products-theme {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .search-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .search-title i {
            margin-left: 10px;
        }
        
        /* Search Input */
        .search-input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-input-wrapper input {
            width: 100%;
            padding: 18px 25px 18px 60px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .search-input-wrapper input:focus {
            outline: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .search-input-wrapper .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.3rem;
        }
        
        /* Filter Grid */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Custom Select */
        .custom-select-wrapper {
            position: relative;
        }
        
        .custom-select-wrapper select {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-left: 40px;
        }
        
        .custom-select-wrapper select:focus {
            outline: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .custom-select-wrapper select:disabled {
            background: rgba(255, 255, 255, 0.5);
            cursor: not-allowed;
        }
        
        .custom-select-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            pointer-events: none;
        }
        
        .custom-select-wrapper .select-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 0.9rem;
        }
        
        /* Search Button */
        .search-btn {
            background: #fff;
            color: #667eea;
            border: none;
            padding: 15px 50px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        
        .search-btn i {
            transition: transform 0.3s ease;
        }
        
        .search-btn:hover i {
            transform: translateX(-5px);
        }
        
        /* Reset Button */
        .reset-btn {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.5);
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .reset-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: #fff;
            color: #fff;
            text-decoration: none;
        }
        
        /* Active Filters */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            justify-content: center;
        }
        
        .filter-tag {
            background: rgba(255, 255, 255, 0.25);
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(5px);
        }
        
        .filter-tag i {
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .filter-tag i:hover {
            opacity: 1;
        }
        
        /* Results Count */
        .results-info {
            background: #fff;
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .results-count {
            font-size: 1.1rem;
            color: #333;
        }
        
        .results-count strong {
            color: #667eea;
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
        
        .search-filter-section {
            animation: fadeInUp 0.5s ease;
        }
        
        .filter-grid > div {
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
        }
        
        .filter-grid > div:nth-child(1) { animation-delay: 0.1s; }
        .filter-grid > div:nth-child(2) { animation-delay: 0.15s; }
        .filter-grid > div:nth-child(3) { animation-delay: 0.2s; }
        .filter-grid > div:nth-child(4) { animation-delay: 0.25s; }
        .filter-grid > div:nth-child(5) { animation-delay: 0.3s; }
    </style>

    <!-- Shop Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pt-5">
            <!-- Shop Product Start -->
            <div class="col-12 mt-5">
                <div class="col-12 pb-1">
                    <!-- Search & Filter Section -->
                    <div class="search-filter-section {{ request('main_category_id') == 13 ? 'products-theme' : 'books-theme' }}">
                        <h3 class="search-title">
                            @if(request('main_category_id') == 13)
                                <i class="fas fa-box-open"></i>
                                ابحث عن المنتجات
                            @else
                                <i class="fas fa-book-open"></i>
                                ابحث عن الكتب والمذكرات
                            @endif
                        </h3>
                        
                        <form action="{{ route('user.shop') }}" method="GET" id="search-form">
                            <!-- Search Input -->
                            <div class="search-input-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" name="title" 
                                    placeholder="{{ request('main_category_id') == 13 ? 'اكتب اسم المنتج الذي تبحث عنه...' : 'اكتب اسم الكتاب أو المذكرة...' }}"
                                    value="{{ request('title') }}"
                                    autocomplete="off">
                            </div>
                            
                            @if (!request('color') && !request('size'))
                                <div class="filter-grid">
                                    @if (request('main_category_id') != 13)
                                        <!-- القسم -->
                                        <div class="custom-select-wrapper">
                                            <i class="fas fa-layer-group select-icon"></i>
                                            <select name="main_category_id" id="main-category-select">
                                                <option value="">📁 اختر القسم</option>
                                                @foreach ($main_categories as $main_category)
                                                    <option value="{{ $main_category->id }}"
                                                        {{ request('main_category_id') == $main_category->id ? 'selected' : '' }}>
                                                        {{ $main_category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- المرحلة التعليمية -->
                                        <div class="custom-select-wrapper">
                                            <i class="fas fa-graduation-cap select-icon"></i>
                                            <select name="stage_id" id="stage-select">
                                                <option value="">🎓 المرحلة التعليمية</option>
                                                @foreach ($stages as $stage)
                                                    <option value="{{ $stage->id }}"
                                                        {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                                        {{ $stage->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- الصف الدراسي -->
                                        <div class="custom-select-wrapper">
                                            <i class="fas fa-chalkboard select-icon"></i>
                                            <select name="slider_id" id="slider-select" {{ !request('stage_id') ? 'disabled' : '' }}>
                                                <option value="">📚 الصف الدراسي</option>
                                                @foreach ($sliders as $slider)
                                                    <option value="{{ $slider->id }}"
                                                        data-stage-id="{{ $slider->stage_id }}"
                                                        {{ request('slider_id') == $slider->id ? 'selected' : '' }}>
                                                        {{ $slider->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- المواد -->
                                        <div class="custom-select-wrapper">
                                            <i class="fas fa-book select-icon"></i>
                                            <select name="category_id">
                                                <option value="">📖 اختر المادة</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- المدرسين -->
                                        <div class="custom-select-wrapper">
                                            <i class="fas fa-chalkboard-teacher select-icon"></i>
                                            <select name="brand_id">
                                                <option value="">👨‍🏫 اختر المدرس</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}"
                                                        {{ request('brand_id') == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Buttons -->
                            <div class="text-center mt-4">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                    بحث الآن
                                </button>
                                @if(request()->hasAny(['title', 'main_category_id', 'stage_id', 'slider_id', 'category_id', 'brand_id']))
                                    <a href="{{ route('user.shop') }}" class="reset-btn me-2">
                                        <i class="fas fa-times"></i>
                                        مسح الفلاتر
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Active Filters Tags -->
                            @if(request()->hasAny(['title', 'main_category_id', 'stage_id', 'slider_id', 'category_id', 'brand_id']))
                                <div class="active-filters">
                                    @if(request('title'))
                                        <span class="filter-tag">
                                            <i class="fas fa-search"></i>
                                            {{ request('title') }}
                                        </span>
                                    @endif
                                    @if(request('main_category_id'))
                                        <span class="filter-tag">
                                            <i class="fas fa-folder"></i>
                                            {{ $main_categories->find(request('main_category_id'))->name ?? '' }}
                                        </span>
                                    @endif
                                    @if(request('stage_id'))
                                        <span class="filter-tag">
                                            <i class="fas fa-graduation-cap"></i>
                                            {{ $stages->find(request('stage_id'))->title ?? '' }}
                                        </span>
                                    @endif
                                    @if(request('category_id'))
                                        <span class="filter-tag">
                                            <i class="fas fa-book"></i>
                                            {{ $categories->find(request('category_id'))->title ?? '' }}
                                        </span>
                                    @endif
                                    @if(request('brand_id'))
                                        <span class="filter-tag">
                                            <i class="fas fa-user"></i>
                                            {{ $teachers->find(request('brand_id'))->title ?? '' }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Results Info -->
                    <div class="results-info">
                        <div class="results-count">
                            <i class="fas fa-boxes me-2" style="color: #667eea;"></i>
                            عدد النتائج: <strong>{{ $products->total() }}</strong> منتج
                        </div>
                        <div class="view-options">
                            <span class="text-muted">
                                صفحة {{ $products->currentPage() }} من {{ $products->lastPage() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row pb-3 g-5">
                    @include('user.layouts.product')
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stageSelect = document.getElementById('stage-select');
            const sliderSelect = document.getElementById('slider-select');
            
            if (!stageSelect || !sliderSelect) return;
            
            // Store all slider options for filtering
            const allSliders = Array.from(sliderSelect.options);

            // Function to filter sliders based on selected stage
            function filterSliders(selectedStageId) {
                // Clear current options except the first one
                sliderSelect.innerHTML = '<option value="">📚 الصف الدراسي</option>';
                
                if (selectedStageId) {
                    // Enable slider select
                    sliderSelect.disabled = false;
                    
                    // Filter and add matching sliders
                    const filteredSliders = allSliders.filter(option => 
                        option.dataset.stageId === selectedStageId
                    );
                    filteredSliders.forEach(option => {
                        sliderSelect.appendChild(option.cloneNode(true));
                    });
                } else {
                    // Disable slider select when no stage is selected
                    sliderSelect.disabled = true;
                    sliderSelect.value = '';
                }
            }

            // Handle stage selection change
            stageSelect.addEventListener('change', function() {
                const selectedStageId = this.value;
                filterSliders(selectedStageId);
            });

            // Initialize on page load
            const initialStageId = stageSelect.value;
            if (initialStageId) {
                filterSliders(initialStageId);
                // Restore selected slider
                const selectedSliderId = '{{ request('slider_id') }}';
                if (selectedSliderId) {
                    sliderSelect.value = selectedSliderId;
                }
            } else {
                sliderSelect.disabled = true;
            }
        });
    </script>
@endsection

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6831a51ce923fe1912297913/1is0upujk';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
