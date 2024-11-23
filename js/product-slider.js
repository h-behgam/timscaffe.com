(function() {
    var swiper = new Swiper('.swiper2', {
        slidesPerView: 4,
        // slidesPerView: 'auto',
        initialSlide  : 2,
        spaceBetween: 10,
        // centeredSlides:true,
        breakpoints: {
            360: {
            slidesPerView: 1,
            spaceBetween: 40,
          },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
            slidesPerView: 3,
            spaceBetween: -40,
          },
          1200: {
            slidesPerView: 4,
            spaceBetween: 10,
          },
        },
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
})();

(function() {
  var swiper = new Swiper('.swiper1', {
      slidesPerView: 3,
      // slidesPerView: 'auto',
      // initialSlide  : 2,
      // spaceBetween: 10,
      // centeredSlides:true,
      breakpoints: {
          360: {
          slidesPerView: 1,
          spaceBetween: 40,
        },
          768: {
              slidesPerView: 2,
              spaceBetween: 30,
          },
          1024: {
          slidesPerView: 3,
          spaceBetween: -40,
        },
        1200: {
          slidesPerView: 3,
          spaceBetween: 10,
        },
      },
      // Navigation arrows
      navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
      },
  });
})();