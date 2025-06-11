<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سامانه مدیریت ساختمان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="{{ asset('css/index.css') }}" rel="stylesheet" />
</head>
<body>

    <header>
        <div class="container">
            <img src="/images/32.jpg" alt="لوگو" class="me-2" />
            <h1>سامانه مدیریت ساختمان</h1>
            <button class="btn btn-custom"><a href="{{route('auth')}}">ورود / ثبت‌نام</a></button>
        </div>
    </header>

    <nav class="nav-quick">
        <div class="container">
            <a href="#about">درباره ما</a>
            <a href="#buildings">ساختمان‌های همکار</a>
            <a href="#features">امکانات</a>
            <a href="#faq">سوالات متداول</a>
        </div>
    </nav>

    <section class="slider">
        <img src="/images/23.jpg" alt="عکس مدیریت ساختمان" />
        <div class="slider-content">
            <h2>همراه هوشمند مدیریت ساختمان</h2>
            <p>راهکاری نوین برای مدیریت شارژ، ارتباط با ساکنین و امور مالی</p>
        </div>
    </section>

    <section id="about" class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>درباره ما</h2>
                <p>سامانه مدیریت ساختمان، بستری مدرن برای رسیدگی به امور مالی، تعمیرات، مدیریت شارژ و ارتباط با ساکنین است. با هدف شفافیت، سهولت و نظم، این سامانه طراحی شده تا مدیران ساختمان‌ها و ساکنین با آرامش بیشتری مدیریت امور را انجام دهند.</p>
            </div>
            <div class="col-md-6">
                <img src="/images/12.jpg" alt="درباره ما" />
            </div>
        </div>
    </section>

    <section id="buildings" class="container">
        <h2 class="text-center">ساختمان‌های همکار</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 col-sm-6">
                <img src="/images/3 (2).jpg" alt="ساختمان یاس" />
                <div class="building-name">ساختمان یاس</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <img src="/images/4.jpg" alt="مجتمع مهر" />
                <div class="building-name">مجتمع مهر</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <img src="/images/7.jpg" alt="برج آسمان" />
                <div class="building-name">برج آسمان</div>
            </div>
            <div class="col-md-3 col-sm-6">
                <img src="/images/9.jpg" alt="برج نارنج" />
                <div class="building-name">برج نارنج</div>
            </div>
        </div>
    </section>

    <section id="features" class="container">
        <h2 class="text-center">امکانات سامانه</h2>
        <div class="row text-center">
            <div class="col-md-3 col-sm-6">
                <i class="bi bi-credit-card icon-feature"></i>
                <h5>مدیریت شارژ و پرداخت</h5>
                <p>صدور و پرداخت صورتحساب‌ها به صورت آنلاین</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="bi bi-people icon-feature"></i>
                <h5>مدیریت ساکنین</h5>
                <p>ثبت و پیگیری اطلاعات ساکنین و مالکین</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <i class="bi bi-tools icon-feature"></i>
                <h5>درخواست تعمیرات</h5>
                <p>ثبت و پیگیری درخواست‌های تعمیر و نگهداری</p>
            </div>
            {{-- <div class="col-md-3 col-sm-6">
                <i class="bi bi-chat-dots icon-feature"></i>
                <h5>ارتباط مستقیم</h5>
                <p>ارتباط بین مدیر و ساکنین به صورت پیام‌رسانی</p>
            </div> --}}
        </div>
    </section>

    <section id="faq" class="container faq">
        <h2 class="text-center">سوالات متداول</h2>
        <div class="accordion" id="accordionFAQ">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        چگونه می‌توانم شارژ ساختمان را پرداخت کنم؟
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        پرداخت شارژ به صورت آنلاین از طریق پنل کاربری انجام می‌شود. کافی است وارد بخش صورتحساب‌ها شده و گزینه پرداخت را انتخاب کنید.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        چگونه درخواست تعمیرات ثبت کنم؟
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        در پنل کاربری، به بخش درخواست‌های تعمیرات رفته و فرم مربوطه را پر کنید. پس از ثبت، مدیر ساختمان درخواست شما را بررسی خواهد کرد.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        آیا می‌توانم صورتحساب‌ها را به صورت گروهی پرداخت کنم؟
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        بله، امکان پرداخت گروهی صورتحساب‌ها در پنل ساکنین فراهم شده است تا پرداخت‌ها راحت‌تر و سریع‌تر انجام شود.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>تماس با ما: <a href="tel:+98211234567" style="color: inherit;">021-1234567</a> | <a href="mailto:info@yourdomain.com" style="color: inherit;">info@yourdomain.com</a></p>
            <div class="social-icons mb-3">
                <a href="#" class="bi bi-instagram"></a>
                <a href="#" class="bi bi-telegram"></a>
                <a href="#" class="bi bi-linkedin"></a>
            </div>
            <small>© 2025 سامانه مدیریت ساختمان. همه حقوق محفوظ است.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // انیمیشن نمایش بخش‌ها با اسکرول
        function revealSections() {
            const sections = document.querySelectorAll('section');
            const windowHeight = window.innerHeight;
            sections.forEach(section => {
                const top = section.getBoundingClientRect().top;
                if (top < windowHeight - 150) {
                    section.classList.add('visible');
                }
            });
        }

        window.addEventListener('scroll', revealSections);
        window.addEventListener('load', revealSections);
        document.addEventListener('DOMContentLoaded', revealSections);
    </script>
</body>
</html>
