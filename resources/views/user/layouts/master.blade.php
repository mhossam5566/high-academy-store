<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="High Academy Store" name="description">
    <meta name="og:image" content="@yield('book-image')">
    <meta name="keywords" content="@yield('keywords', 'High academy store, كتب دراسية, كتب خارجية, مدرسين أونلاين مصر, چيو ماجد أمام, مستر خالد صقر, كتاب الامتحان, كتاب الأضواء, كتاب المعاصر, كتاب سلاح التلميذ, كتاب جيم, كتاب كيان, كتاب نيوتن, اللواء رضا الفاروق, مستر محمد عبد الجواد, دكتور محمد أيمن, مستر محمد عبد المعبود, دكتور كيرلس, دكتور أحمد الجوهري, مستر عبد الحميد حامد, انجلشاوي, كتاب الدكتور فى الفيزياء ,كتاب دكتور كيرلس, هاى اكاديمي, كتاب خالد صقر, كتاب مستر خالد صقر, كتاب ا خالد صقر, كتاب جيو ماجد امام , كتاب مستر محمد عبدالجواد, كتاب دكتور احمد الجوهرى, كتاب مستر محمد صلاح, كتاب مسرت شريف المصرى, كتاب اللواء رضا فاروق, كتاب دكتور محمد ايمن , كتاب مستر محمود مجدى, كتاب مستر عبدالحميد حامد, كتاب مستر محمد صلاح, كتب خارجيه')">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '950938886923910');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=950938886923910&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->


    @include('user.layouts.css')
     <style>
        /* Ensure body doesn't scroll when spinner is visible */
        body.no-scroll {
            overflow: hidden;
        }
        .pencil {
	display: block;
	width: 10em;
	height: 10em;
}
body{
    overflow-x: hidden;
}
.pencil__body1,
.pencil__body2,
.pencil__body3,
.pencil__eraser,
.pencil__eraser-skew,
.pencil__point,
.pencil__rotate,
.pencil__stroke {
	animation-duration: 2s;
	animation-timing-function: linear;
	animation-iteration-count: infinite;
}
.pencil__body1,
.pencil__body2,
.pencil__body3 {
	transform: rotate(-90deg);
}
.pencil__body1 {
	animation-name: pencilBody1;
}
.pencil__body2 {
	animation-name: pencilBody2;
}
.pencil__body3 {
	animation-name: pencilBody3;
}
.pencil__eraser {
	animation-name: pencilEraser;
	transform: rotate(-90deg) translate(49px,0);
}
.pencil__eraser-skew {
	animation-name: pencilEraserSkew;
	animation-timing-function: ease-in-out;
}
.pencil__point {
	animation-name: pencilPoint;
	transform: rotate(-90deg) translate(49px,-30px);
}
.pencil__rotate {
	animation-name: pencilRotate;
}
.pencil__stroke {
	animation-name: pencilStroke;
	transform: translate(100px,100px) rotate(-113deg);
}

/* Animations */
@keyframes pencilBody1 {
	from,
	to {
		stroke-dashoffset: 351.86;
		transform: rotate(-90deg);
	}
	50% {
		stroke-dashoffset: 150.8; /* 3/8 of diameter */
		transform: rotate(-225deg);
	}
}
@keyframes pencilBody2 {
	from,
	to {
		stroke-dashoffset: 406.84;
		transform: rotate(-90deg);
	}
	50% {
		stroke-dashoffset: 174.36;
		transform: rotate(-225deg);
	}
}
@keyframes pencilBody3 {
	from,
	to {
		stroke-dashoffset: 296.88;
		transform: rotate(-90deg);
	}
	50% {
		stroke-dashoffset: 127.23;
		transform: rotate(-225deg);
	}
}
@keyframes pencilEraser {
	from,
	to {
		transform: rotate(-45deg) translate(49px,0);
	}
	50% {
		transform: rotate(0deg) translate(49px,0);
	}
}
@keyframes pencilEraserSkew {
	from,
	32.5%,
	67.5%,
	to {
		transform: skewX(0);
	}
	35%,
	65% {
		transform: skewX(-4deg);
	}
	37.5%, 
	62.5% {
		transform: skewX(8deg);
	}
	40%,
	45%,
	50%,
	55%,
	60% {
		transform: skewX(-15deg);
	}
	42.5%,
	47.5%,
	52.5%,
	57.5% {
		transform: skewX(15deg);
	}
}
@keyframes pencilPoint {
	from,
	to {
		transform: rotate(-90deg) translate(49px,-30px);
	}
	50% {
		transform: rotate(-225deg) translate(49px,-30px);
	}
}
@keyframes pencilRotate {
	from {
		transform: translate(100px,100px) rotate(0);
	}
	to {
		transform: translate(100px,100px) rotate(720deg);
	}
}
@keyframes pencilStroke {
	from {
		stroke-dashoffset: 439.82;
		transform: translate(100px,100px) rotate(-113deg);
	}
	50% {
		stroke-dashoffset: 164.93;
		transform: translate(100px,100px) rotate(-113deg);
	}
	75%,
	to {
		stroke-dashoffset: 439.82;
		transform: translate(100px,100px) rotate(112deg);
	}
}

    </style>
</head>

<body class="no-scroll" style="font-family: --font-family-sans-serif !important;">
    @if($siteNotification)
        <div id="site-notification" class="site-notification-wrapper d-none">
            <div class="notification-character-container">
                <!-- Detailed Cartoon Child -->
                <div class="character-svg">
                    <svg viewBox="0 0 240 280" xmlns="http://www.w3.org/2000/svg">
                        <!-- Backpack (Back) -->
                        <rect x="90" y="160" width="60" height="70" rx="15" fill="#1a1a2e" />
                        
                        <!-- Legs (Wider Stance) -->
                        <rect x="95" y="230" width="16" height="40" fill="#2c3e50" />
                        <rect x="129" y="230" width="16" height="40" fill="#2c3e50" />
                        
                        <!-- Shoes -->
                        <path d="M95 270 h-15 q-5 0 -5 -5 v-5 h20 Z" fill="#1a1a2e" />
                        <path d="M129 270 h18 q5 0 5 -5 v-5 h-23 Z" fill="#1a1a2e" />
                        
                        <!-- Body/Shirt -->
                        <rect x="90" y="155" width="60" height="85" rx="15" fill="#e07b39" />
                        <!-- Shirt Collar -->
                        <path d="M90 165 L120 178 L150 165 L150 155 L90 155 Z" fill="#c06a30" />
                        
                        <!-- Backpack Straps -->
                        <rect x="98" y="165" width="10" height="65" rx="5" fill="#1a1a2e" opacity="0.8" />
                        <rect x="132" y="165" width="10" height="65" rx="5" fill="#1a1a2e" opacity="0.8" />

                        <!-- Waving Arm (Right) -->
                        <g class="arm-waving">
                            <!-- Shoulder connection -->
                            <circle cx="150" cy="175" r="8" fill="#FFDBAC" />
                            <path d="M150 175 Q190 175 210 120" stroke="#FFDBAC" stroke-width="16" fill="none" stroke-linecap="round" />
                            <!-- Hand -->
                            <circle cx="210" cy="120" r="14" fill="#FFDBAC" />
                            <!-- Fingers -->
                            <circle cx="200" cy="105" r="5" fill="#FFDBAC" />
                            <circle cx="210" cy="100" r="5" fill="#FFDBAC" />
                            <circle cx="220" cy="105" r="5" fill="#FFDBAC" />
                        </g>

                        <!-- Holding Arm & Integrated Hand (Left) -->
                        <g class="arm-holding">
                            <!-- Shoulder connection -->
                            <circle cx="90" cy="175" r="8" fill="#FFDBAC" />
                            <path d="M90 175 Q40 175 15 195" stroke="#FFDBAC" stroke-width="16" fill="none" stroke-linecap="round" />
                            <!-- Hand Grabbing (Overlap the board) -->
                            <rect x="0" y="180" width="35" height="45" rx="12" fill="#FFDBAC" stroke="#1a1a2e" stroke-width="3" />
                            <path d="M35 185 v35" stroke="#1a1a2e" stroke-width="3" stroke-linecap="round" />
                        </g>

                        <!-- Head -->
                        <circle cx="120" cy="110" r="50" fill="#FFDBAC" />
                        <!-- Ears -->
                        <circle cx="70" cy="110" r="10" fill="#FFDBAC" />
                        <circle cx="170" cy="110" r="10" fill="#FFDBAC" />
                        
                        <!-- Hair -->
                        <path d="M70 110 Q70 55 120 50 Q170 55 170 110 L170 100 Q170 65 120 60 Q70 65 70 100 Z" fill="#4B2C20" />
                        
                        <!-- Face -->
                        <circle cx="95" cy="125" r="8" fill="#ffb6c1" opacity="0.5" />
                        <circle cx="145" cy="125" r="8" fill="#ffb6c1" opacity="0.5" />
                        <circle cx="105" cy="105" r="6" fill="white" />
                        <circle cx="106" cy="105" r="3" fill="#333" />
                        <circle cx="135" cy="105" r="6" fill="white" />
                        <circle cx="136" cy="105" r="3" fill="#333" />
                        <path d="M105 135 Q120 150 135 135" stroke="#333" stroke-width="4" fill="none" stroke-linecap="round" />
                    </svg>
                </div>

                <!-- Sign Board -->
                <div class="notification-sign">
                    <div class="sign-board-content">
                        <button type="button" class="close-notification-new" onclick="closeSiteNotification()">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="sign-inner-body">
                            {!! $siteNotification->content !!}
                        </div>
                    </div>
                    <!-- Pole -->
                    <div class="sign-pole-container">
                        <div class="sign-pole"></div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .site-notification-wrapper {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 10001;
                pointer-events: none;
                width: auto;
                max-width: 95vw;
            }
            .notification-character-container {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                pointer-events: auto;
                position: relative;
            }
            .character-svg {
                width: 280px; /* Bigger boy */
                height: auto;
                filter: drop-shadow(0 15px 30px rgba(0,0,0,0.35));
                margin-left: -90px; /* Deeper overlap for the integrated hand */
                order: 2;
                z-index: 10;
                transform: translateX(20px);
            }
            .notification-sign {
                order: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 50px;
                position: relative;
                z-index: 5;
                animation: floatSign 4s ease-in-out infinite;
            }
            .sign-board-content {
                background: #fdfaf5; /* Warm parchment color */
                border: 6px solid #1a1a2e;
                border-radius: 25px;
                padding: 35px;
                box-shadow: 0 25px 50px rgba(0,0,0,0.25);
                position: relative;
                min-width: 380px;
                max-width: 580px;
            }
            .sign-inner-body {
                max-height: 380px;
                overflow-y: auto;
                font-family: 'Cairo', sans-serif;
                font-size: 19px;
                color: #1a1a2e;
                line-height: 1.6;
                text-align: center;
                font-weight: 600;
            }
            .sign-inner-body img {
                max-width: 100%;
                height: auto;
                border-radius: 12px;
                margin-top: 15px;
                border: 3px solid rgba(0,0,0,0.05);
            }
            .sign-pole {
                width: 22px;
                height: 90px;
                background: linear-gradient(90deg, #4B2C20 0%, #7d4d3a 50%, #4B2C20 100%);
                border: 4px solid #1a1a2e;
                border-top: none;
                border-radius: 0 0 10px 10px;
            }
            .close-notification-new {
                position: absolute;
                top: -22px;
                left: -22px;
                background: #e07b39;
                color: white;
                border: 4px solid #1a1a2e;
                border-radius: 50%;
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 20px;
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                z-index: 12;
            }
            .close-notification-new:hover {
                transform: scale(1.15) rotate(90deg);
                background: #1a1a2e;
                box-shadow: 0 5px 15px rgba(224, 123, 57, 0.4);
            }

            /* Animations */
            @keyframes armWaving {
                0%, 100% { transform: rotate(-8deg); }
                50% { transform: rotate(12deg); }
            }
            .arm-waving {
                animation: armWaving 1.5s ease-in-out infinite;
                transform-origin: 150px 175px;
            }
            @keyframes floatSign {
                0%, 100% { transform: translateY(0) rotate(-0.5deg); }
                50% { transform: translateY(-12px) rotate(0.5deg); }
            }

            /* Mobile Adjustments */
            @media (max-width: 768px) {
                .site-notification-wrapper {
                    bottom: 10px;
                    right: 10px;
                }
                .character-svg {
                    width: 180px;
                    margin-left: -60px;
                    transform: translateX(10px);
                }
                .sign-board-content {
                    min-width: 280px;
                    padding: 20px;
                    border-width: 4px;
                }
                .sign-inner-body {
                    font-size: 16px;
                }
                .close-notification-new {
                    width: 38px;
                    height: 38px;
                    font-size: 16px;
                    top: -15px;
                    left: -15px;
                    border-width: 3px;
                }
                .sign-pole {
                    height: 50px;
                    width: 16px;
                    border-width: 3px;
                }
            }
        </style>

        <script>
            function closeSiteNotification() {
                document.getElementById('site-notification').classList.add('d-none');
                sessionStorage.setItem('site_notification_closed', 'true');
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem('site_notification_closed')) {
                    document.getElementById('site-notification').classList.remove('d-none');
                }
            });
        </script>
    @endif
        </style>

        <script>
            function closeSiteNotification() {
                document.getElementById('site-notification').classList.add('d-none');
                sessionStorage.setItem('site_notification_closed', 'true');
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem('site_notification_closed')) {
                    document.getElementById('site-notification').classList.remove('d-none');
                }
            });
        </script>
    @endif
    <style>
    	.whatsapp-float {
    		z-index: 100;
    		position: fixed;
    		bottom: 30px;
    		left: 30px;
    		background-color: #25D366;
    		width: 50px;
    		height: 50px;
    		border-radius: 50%;
    		display: flex;
    		align-items: center;
    		justify-content: center;
    		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    		text-decoration: none;
    	}
    	.whatsapp-float img {
    		width: 30px;
    		height: 30px;
    	}
	</style>
	<a href="https://whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23" class="whatsapp-float" target="_blank">
		<img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
	</a>
    <section
        class="loading position-fixed top-0 end-0 bottom-0 start-0 d-flex justify-content-center align-items-center"
        style="z-index: 9999;background: hsl(223,90%,90%);">
        <svg class="pencil" viewBox="0 0 200 200" width="200px" height="200px" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <clipPath id="pencil-eraser">
                    <rect rx="5" ry="5" width="30" height="30"></rect>
                </clipPath>
            </defs>
            <circle class="pencil__stroke" r="70" fill="none" stroke="currentColor" stroke-width="2"
                stroke-dasharray="439.82 439.82" stroke-dashoffset="439.82" stroke-linecap="round"
                transform="rotate(-113,100,100)" />
            <g class="pencil__rotate" transform="translate(100,100)">
                <g fill="none">
                    <circle class="pencil__body1" r="64" stroke="hsl(223,90%,50%)" stroke-width="30"
                        stroke-dasharray="402.12 402.12" stroke-dashoffset="402" transform="rotate(-90)" />
                    <circle class="pencil__body2" r="74" stroke="hsl(223,90%,60%)" stroke-width="10"
                        stroke-dasharray="464.96 464.96" stroke-dashoffset="465" transform="rotate(-90)" />
                    <circle class="pencil__body3" r="54" stroke="hsl(223,90%,40%)" stroke-width="10"
                        stroke-dasharray="339.29 339.29" stroke-dashoffset="339" transform="rotate(-90)" />
                </g>
                <g class="pencil__eraser" transform="rotate(-90) translate(49,0)">
                    <g class="pencil__eraser-skew">
                        <rect fill="hsl(223,90%,70%)" rx="5" ry="5" width="30" height="30" />
                        <rect fill="hsl(223,90%,60%)" width="5" height="30" clip-path="url(#pencil-eraser)" />
                        <rect fill="hsl(223,10%,90%)" width="30" height="20" />
                        <rect fill="hsl(223,10%,70%)" width="15" height="20" />
                        <rect fill="hsl(223,10%,80%)" width="5" height="20" />
                        <rect fill="hsla(223,10%,10%,0.2)" y="6" width="30" height="2" />
                        <rect fill="hsla(223,10%,10%,0.2)" y="13" width="30" height="2" />
                    </g>
                </g>
                <g class="pencil__point" transform="rotate(-90) translate(49,-30)">
                    <polygon fill="hsl(33,90%,70%)" points="15 0,30 30,0 30" />
                    <polygon fill="hsl(33,90%,50%)" points="15 0,6 30,0 30" />
                    <polygon fill="hsl(223,10%,10%)" points="15 0,20 10,10 10" />
                </g>
            </g>
        </svg>
    </section>
    <!-- Navbar Start -->
    <div id="header-ajax">
        @include('user.layouts.nav')
    </div>
    <!-- Navbar End -->

    @yield('content')


    <!-- Footer Start -->
    @include('user.layouts.footer')
    <!-- Footer End -->



    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top" style="width:50px;"><i class="fa fa-angle-double-up"></i></a>


    @include('user.layouts.js')
   <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                console.log("Page Loded")
                // Hide the loading spinner
                document.querySelector('.loading').classList.remove('d-flex');
                document.querySelector('.loading').classList.add('d-none');

                // Re-enable scroll after the page is loaded
                document.body.classList.remove('no-scroll');
            }, 500);
        });
    </script>

</body>

</html>
