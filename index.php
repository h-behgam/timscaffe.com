<?php
declare(strict_types=1);

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

function getInstagramMedia() {

    require realpath(dirname(__FILE__)) . '/insta/vendor/autoload.php';
    $credentials = include_once realpath(dirname(__FILE__)) . '/insta/credentials.php';
    $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/insta/cache');

    try {
        $api = new Api($cachePool);
        $api->login($credentials->getLogin(), $credentials->getPassword());

        $profile = $api->getProfile('tims_caffe');

        $result = $profile->getMedias();

        printMedias($profile->getMedias());
    } catch (InstagramException $e) {
        print_r($e->getMessage());
    } catch (CacheException $e) {
        print_r($e->getMessage());
    }
}

function printMedias(array $medias) {
    $i = 0;
    foreach ($medias as $media) {
        $name = 'image' . $i++ . '.jpg';
        $content = file_get_contents($media->getThumbnailSrc());
        file_put_contents('insta/tmp/' . $name, $content);

//        echo '<div class="product-card swiper-slide"><div class="product-card-content"><div class="product-banner">'
//        . '<a href="' . $media->getLink() . '"><img src="' . 'insta/tmp/' . $name . '" alt="محصولات تیمز"><a/></div></div></div>';


        echo '<div class="product-card swiper-slide"><div class="product-card-content"><div class="product-banner">'
        . '<a href="' . $media->getLink() . '"><img src="' . 'insta/tmp/' . $name . '" alt="محصولات تیمز"></a>'
        . '</div></div></div>' . PHP_EOL;

//        echo '<div class="product-card swiper-slide"><div class="product-card-content"><div class="product-banner">'
//        . '<img src="' . 'insta/tmp/' . $name . '" alt="محصولات تیمز"></div><h3 class="product-title">' . $media->getCaption() . '</h3></div></div>';
//            echo 'ID           : ' . $media->getDisplaySrc() . "\n";
//            echo 'ID           : ' . $media->getId() . "\n";
//            echo 'Caption      : ' . $media->getCaption() . "\n";
//            echo 'thumbnailSrc : ' . $media->getThumbnailSrc() . "\n";
//            echo 'Link         : ' . $media->getLink() . "\n";
//            echo 'Likes        : ' . $media->getLikes() . "\n";
//            echo 'comments     : ' . $media->getComments() . "\n";
//            echo 'Date         : ' . $media->getDate()->format('Y-m-d h:i:s') . "\n\n";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Times | Home</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link href="assets/lib/swiper/swiper.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/style.css">
        <!--<link href="css/product-slider.css" rel="stylesheet" type="text/css"/>-->
    </head>
    <body>
        <div class="page-content-wrapper">
            <header class="header-section">
                <div class="container">
                    <nav class="header-nav">
                        <div class="nav-toggle">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="./index.php">
                                    صفحه اول
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="./product-list.html">
                                    محصولات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="./recipes.html">
                                    طرز تهیه
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="./about-us.html">
                                    درباره ما
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="./contact-us.html">
                                    تماس با ما
                                </a>
                            </li>
                        </ul>
                        <div class="logo-item">
                            <a class="logo-link" href="./index.php">
                                <img class="logo-icon"
                                     src="assets/icon/logo.svg"
                                     alt="Tim's">
                            </a>
                        </div>
                    </nav>
                </div>
            </header>

            <section class="coffee-beans">
<!--                <img class="bean-1" src="assets/img/coffee-bean.svg">
                <img class="bean-2" src="assets/img/coffee-bean.svg">
                <img class="bean-3" src="assets/img/coffee-bean.svg">
                 <img class="bean-4" src="assets/img/coffee-bean.svg"> 
                <img class="bean-5" src="assets/img/coffee-shadow.svg">
                <img class="bean-6" src="assets/img/coffee-bean.svg">
                <img class="bean-8" src="assets/img/coffee-bean.svg">
                <img class="bean-9" src="assets/img/coffee-focus.svg">
                <img class="bean-10" src="assets/img/coffee-shadow.svg">
                <img class="bean-12" src="assets/img/coffee-bean.svg">-->
            </section>

            <!-- main section -->
            <section class="main-section">
                <div class="container">
                    <div class="main-section-content">
                        <div class="main-section-en">
                            <!--<h2>TIM’S TIME</h2>-->
                            <img src="assets/img/asset-6.png" alt=""/>
                        </div>
                        <div class="contents">
                            <h2 class="main-title">
                                حالا وقت تیمزه!
                            </h2>
                            <h3 class="main-desc">
                                حتی قهوه خورای حرفه ای هم تیمز رو می پسندن!
                            </h3>
                            <h4 class="main-subtitle justify">
                                قهوه های فوری تیمز از دانه های با کیفیت قهوه از سراسر جهان تهیه شده است
                                که طعم غنی و اصیل تری دارد و ویژگی های واقعی قهوه در آن حفظ شده است.
                                محصولات تیمز مناسب برای انواع ذائقه دوستداران قهوه است
                                و نیاز روزانه افراد به قهوه را سریع و ساده برطرف می کند.
                                در کارخانه تیمز، دانه های قهوه پس از فرایند بو دادن در زمان کمی 
                                آسیاب و بسته بندی می شوند تا کیفیت و تازگی قهوه حفظ شود.
                            </h4>
                            <div class="call-to-actions">
                                <a class="btn btn-primary btn-pill" href="./product-list.html">
                                    مشاهده محصولات
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="banner-side">
                        <img src="assets/img/Tim's-Coffee-Banner-06-2.png" alt="">
                    </div>
                </div>
                <img class="bean-20" src="assets/img/asset.png" alt=""/>
            </section>
            <!-- main section end -->

            <!-- intro section -->
            <section class="intro-section">
                <img class="bean-21" src="assets/img/asset.png" alt=""/>
                <div class="container">
                    <div class="section-content">
                        <h3 class="section-title">
                            قهوه تیمز، ایده آل برای سبک زندگی امروز
                        </h3>
                        <h3 class="section-subtitle justify">
                            ما در تلاشیم تا بهترین قهوه ممکن را فراهم کنیم تا بتوانید
                            با صرف کمترین زمان با کیفیت ترین قهوه را نوش جان کنید.
                            با قهوه های فوری تیمز هر زمان از روز می توانید از یک فنجان قهوه تازه لذت ببرید.
                            قهوه تیمز را می توانید همه جا با خود ببرید و هروقت که خواستید قهوه با کیفیت درست کنید.
                            برای درست کردن یه کاپوچینوی خامه ای و خوشمزه نیازی به دستگاه قهوه
                            و صرف وقت زیاد ندارید. کافیه دستور العمل
                            کاپوچینوی تیمز رو دنبال کنید و از طعمش لذت ببرید.
                        </h3>
                        <a class="btn btn-primary btn-pill" href="./about-us.html">
                            با تیمز بیشتر آشنا شوید
                        </a>
                    </div>
                </div>
                <img class="bean-41" src="assets/img/asset-2.png" alt=""/>
            </section>
            <!-- intro section end -->

            <!-- video section -->
            <section class="video-section">
                <div class="video-banner video-promotion">
                    <!-- <img class="banner-img"
                        src="assets/img/video-banner.png"
                        alt=""> -->
                    <img class="play-icon video-play-btn embed-play"
                         src="assets/icon/play.svg"
                         alt="">
                    <div class="video-embed-content">
                        <div id="42077645107">
                            <script type="text/JavaScript"
                                    src="https://www.aparat.com/embed/aoxiz?data[rnddiv]=42077645107&data[responsive]=yes">
                            </script>
                        </div>

                    </div>
                </div>
            </section>
            <!-- video section end -->

            <!-- secound intro section -->
            <section class="secound-intro-section">
                <img class="bean-22" src="assets/img/asset.png" alt=""/>
                <img class="bean-23" src="assets/img/asset.png" alt=""/>
                <div class="container">
                    <div class="section-content">
                        <h3 class="section-title">
                            ویژگی های تیمز
                        </h3>
                        <div class="properties">
                            <div class="property">
                                <h3>تقویت عملکرد مغز</h3>
                                <p class="justify">کافئین که به طور طبیعی در قهوه و چای هم وجود دارد یک داروی محرک است که معمولا برای بهبود عملکردهای ذهنی به کار می‌رود. تاثیر کافئین بر مغز زیاد است. این ماده با مسدود کردن پیام‌رسان‌های عصبی گیرنده‌های آدنوزین، باعث افزایش آمادگی مغز برای حرکت و کار می‌شود.</p>
                            </div>
                            <div class="property">
                                <h3>تقویت سوخت و ساز</h3>
                                <p class="justify">امروزه کافئین به دلیل مزایای خوبی که دارد، در اغلب مکمل‌های چربی‌سوز تجاری استفاده می‌شود. علاوه براین یکی از معدود مواد شناخته‌شده‌ای است که چربی‌های موجود در بافت چربی بدن را جذب کرده و متابولیسم را افزایش می‌دهد.</p>
                            </div>
                            <div class="property">
                                <h3>کاهش خطر دیابت</h3>
                                <p class="justify">قهوه علاوه بر کافئین ترکیبات دیگری نیز در خود گنجانده که ممکن است اثر حفاظتی قهوه در برابر بیماری دیابت نیز از آن ها نشات گرفته باشد.مصرف روزانۀ حدوداً  ۴ تا ۶ فنجان قهوه در روز بدلیل وجود این ترکیبات محافظت کننده خطر ابتلا به دیابت را کاهش می دهد.  </p>
                            </div>
                            <div class="property">
                                <h3>افزایش طول عمر </h3>
                                <p class="justify">ترکیبات موجود در قهوه مملو از آنتی اکسیدان ها و ترکیبات فنولیک است که نقش موثری در افزایش طول عمر و پیشگیری از ابتلا به بیماری های از جمله آلزایمر و سرطان دارد.طبق تحقیقات انجام شده افرادی که قهوه                می نوشند زندگی طولانی تری دارند. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- secound intro section end -->

            <!-- slider section -->
            <section class="slider-section">
                <img class="bean-42" src="assets/img/asset-2.png" alt=""/>
                <h3 class="section-title text-align-center">
                    محصولات تیمز
                </h3>
                <!--<img class="bean-11" src="assets/img/coffee-bean.svg">-->
                <!-- <div class="slider-container slider-container-fix" dir="rtl"> -->
                <div class="slider-container swiper-container swiper-containers swiper1" dir="rtl">
                    <!--<img class="bean-7" src="assets/img/coffee-focus.svg">-->
                    <div class="product-wrapper1 swiper-wrapper">
                        <div class="product-card swiper-slide">
                            <div class="product-card-content">
                                <div class="product-banner">
                                    <img src="assets/img/slide-1.png" alt="محصولات تیمز">
                                </div>
                                <div class="proh">
                                    <h3 class="product-title">
                                        قهوه فوری ۳‍ در ۱ 
                                    </h3>
                                    <a class="btn btn-primary btn-pill" href="./product1.html">
                                        برای مشاهده محصولات کلیک کنید
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="product-card swiper-slide">
                            <div class="product-card-content">
                                <div class="product-banner">
                                    <img src="assets/img/slide-2.png" alt="محصولات تیمز">
                                </div>
                                <div class="proh">
                                    <h3 class="product-title">هات چاکلت</h3>
                                    <a class="btn btn-primary btn-pill" href="./product2.html">
                                        برای مشاهده محصولات کلیک کنید
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product-card swiper-slide">
                            <div class="product-card-content">
                                <div class="product-banner">
                                    <img src="assets/img/slide-3.png" alt="محصولات تیمز">
                                </div>
                                <div class="proh">
                                    <h3 class="product-title">کاپوچــــینو</h3>
                                    <a class="btn btn-primary btn-pill" href="./product3.html">
                                        برای مشاهده محصولات کلیک کنید
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product-card swiper-slide">
                            <div class="product-card-content">
                                <div class="product-banner">
                                    <img src="assets/img/2in1.png" alt="محصولات تیمز">
                                </div>
                                <div class="proh">
                                    <h3 class="product-title">قهوه فوری 2 در 1</h3>
                                    <a class="btn btn-primary btn-pill" href="./product4.html">
                                        برای مشاهده محصولات کلیک کنید
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product-card swiper-slide">
                            <div class="product-card-content">
                                <div class="product-banner">
                                    <img src="assets/img/black.png" alt="محصولات تیمز">
                                </div>
                                <div class="proh">
                                    <h3 class="product-title">قهوه بلک</h3>
                                    <a class="btn btn-primary btn-pill" href="./product5.html">
                                        برای مشاهده محصولات کلیک کنید
                                    </a>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    <div class="swiper-button-prev slider-btn">
                        <img src="assets/icon/asset-10.png" alt=""/>
                        <!--<img src="assets/icon/next.svg" alt=">">-->
                    </div>
                    <div class="swiper-button-next slider-btn">
                        <img src="assets/icon/asset-10.png" alt=""/>
                        <!--<img src="assets/icon/next.svg" alt=">">-->
                    </div>
                </div>
            </section>
            <!-- slider section end -->

            <section class="Third-intro-section">
                <div class="container">
                    <h3 class="section-title text-align-center">
                        رسپی های تیمز
                    </h3>
                    <div class="Third-section-content">
                        <div class="content">
                            <a href="./recepy1.html" target="_blank">
                                <div class="content-overlay"></div>
                                <img class="content-image" src="assets/img/asset-4.jpg">
                                <div class="content-details fadeIn-bottom">
                                    <h3 class="content-title">قهوه های فوری تیمز</h3>
                                    <p class="content-text">از دانه های با کیفیت قهوه از سراسر جهان تهیه شده است</p>
                                </div>
                            </a>
                        </div>
                        <div class="more-content">
                            <div class="content1 content-child ">
                                <a href="./recepy1.html" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" src="assets/img/1.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <h3 class="content-title">قهوه های فوری تیمز</h3>
                                        <p class="content-text">از دانه های با کیفیت قهوه از سراسر جهان تهیه شده است</p>
                                    </div>
                                </a>
                                <div class="more">
                                    <p>قهوه فوری تیمز به خوبی در نوشیدنی حل می شود و پودر اضافی در ته لیوان باقی نمی ماند.</p>
                                    <a href="./recepy1.html" class="btn-more">ادامه مطلب</a>
                                </div>
                            </div>
                            <div class="content1 content-child ">
                                <a href="./recepy1.html" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" src="assets/img/2.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <h3 class="content-title">قهوه های فوری تیمز</h3>
                                        <p class="content-text">از دانه های با کیفیت قهوه از سراسر جهان تهیه شده است</p>
                                    </div>
                                </a>
                                <div class="more">
                                    <p>قهوه فوری تیمز به خوبی در نوشیدنی حل می شود و پودر اضافی در ته لیوان باقی نمی ماند.</p>
                                    <a href="./recepy1.html" class="btn-more">ادامه مطلب</a>
                                </div>
                            </div>
                            <div class="content1 content-child ">
                                <a href="./recepy1.html" target="_blank">
                                    <div class="content-overlay"></div>
                                    <img class="content-image" src="assets/img/3.jpg">
                                    <div class="content-details fadeIn-bottom">
                                        <h3 class="content-title">قهوه های فوری تیمز</h3>
                                        <p class="content-text">از دانه های با کیفیت قهوه از سراسر جهان تهیه شده است</p>
                                    </div>
                                </a>
                                <div class="more">
                                    <p>قهوه فوری تیمز به خوبی در نوشیدنی حل می شود و پودر اضافی در ته لیوان باقی نمی ماند.</p>
                                    <a href="./recepy1.html" class="btn-more">ادامه مطلب</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img class="bean-24" src="assets/img/asset.png" alt=""/>
                <img class="bean-25" src="assets/img/asset.png" alt=""/>
                <img class="bean-43" src="assets/img/asset-2.png" alt=""/>
                <img class="bean-44" src="assets/img/asset-2.png" alt=""/>
            </section>
            <!-- third section end -->

            <section class="slider-section">
                <h3 class="section-title text-align-center">
                    ما رو در اینستاگرام دنبال کنید
                </h3>
                <!--<img class="bean-11" src="assets/img/coffee-bean.svg">-->
                <div class="slider-container swiper-container swiper2" dir="rtl">
<!--                    <img class="bean-13" src="assets/img/coffee-focus.svg">-->
                    <div class="swiper-wrapper">
                        <?php getInstagramMedia() ?>
                    </div>
                    <div class="swiper-button-prev slider-btn">
                        <img src="assets/icon/asset-10.png" alt=""/>
                        <!--<img src="assets/icon/next.svg" alt=">">-->
                    </div>
                    <div class="swiper-button-next slider-btn">
                        <img src="assets/icon/asset-10.png" alt=""/>
                        <!--<img src="assets/icon/next.svg" alt=">">-->
                    </div>
                </div>
            </section>
            <!-- slider section end -->

            <!-- footer -->
            <footer class="footer-section">
                <div class="wrapper">
                    <div class="right-logo">
                        <img class="bean-26" src="assets/img/asset-3.png" alt=""/>
                        <img class="image-tims" src="assets/img/asset-8.png" alt=""/>
                    </div>
                    <div class="info">
                        <div class="description">
                            <h4>دنیـــای تیمـــز</h4>
                            <p class="justify">ما در تلاشیم تا بهترین قهوه ممکن را فراهم کنیم تا بتوانید با صرف کمترین زمان با کیفیت ترین قهوه را نوش جان کنید. با قهوه های فوری تیمز هر زمان از روز  می توانید از یک فنجان قهوه تازه لذت ببرید. قهوه تیمز را می توانید همه جا با خود ببرید و هروقت که خواستید قهوه با کیفیت درست کنید. برای درست کردن یه کاپوچینوی خامه ای و خوشمزه نیازی به دستگاه قهوه و صرف وقت ببرید.</p>
                        </div>
                        <div class="address">
                            <i class="address-icon"></i>
                            <p>دفتــــر مرکزی : تهـــران، خیـابـان وزرا، کوچــه هفتـم،  پلاک ۸</p>
                        </div>
                        <div class="tel">
                            <i class="tel-icon"></i>
                            <p>تلفن تماس : ٨٧۰۰ ٨۸۷۲ ٠٢١ </p>
                        </div>
                    </div>
                    <div class="related">
                        <h4>تیمز</h4>
                        <img class="dash" src="assets/img/asset-1.png" alt=""/>
                        <ul>
                            <li><a href="./product-list.html">محصولات ما</a></li>
                            <li><a href="./catalog.html">کاتالوگ محصولات</a></li>
                            <li><a href="./recipes.html">رسپی ها</a></li>
                            <li>درخواست همکاری</li>
                        </ul>
                    </div>
                    <div class="about">
                        <img class="bean-27" src="assets/img/asset.png" alt=""/>
                        <h4>ارتباط با تیمز</h4>
                        <img class="dash" src="assets/img/asset-1.png" alt=""/>
                        <ul>
                            <li><a href="./about-us.html">درباره ما</a></li>
                            <li><a href="./contact-us.html">تماس با ما</a></li>
                            <li>پیشنهادات و انتقادات</li>
                        </ul>
                    </div>
                    <div class="left-logo">
                        <div class="logo-container">
                            <img src="assets/img/asset-5.png" alt=""/>
                        </div>
                        <div class="social">
                            <div class="social-medi">
                                <a href="https://www.instagram.com/tims_caffe/">tims_caffe</a>
                                <i class="insta-icon"></i>
                            </div>
                            <img class="social-dash" src="assets/img/asset-1.png" alt=""/>
                            <div class="text-insta-icon">
                                <p class="description">ما رو در اینستاگرام دنبال کنید</p>
                            </div>
                        </div>
                    </div>
                </div>
                <img class="bean-45" src="assets/img/asset-2.png" alt=""/>
            </footer>
            <!-- footer end -->
            <a href="#content" class="back-to-top"><img src="assets/icon/up.png" alt=""/></a>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/header.js"></script>
        <script src="js/home.js"></script>
        <script src="assets/lib/swiper/swiper.min.js"></script>
        <script src="js/product-slider.js"></script>
    </body>
</html>