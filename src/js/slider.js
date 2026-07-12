document.addEventListener('DOMContentLoaded', () => {
    const ofertasSlider = document.querySelector('.ofertas__slider');
    if(ofertasSlider){
        new Swiper(ofertasSlider, {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                el: ofertasSlider.querySelector('.swiper-pagination'),
                clickable: true
            },
            navigation: {
                nextEl: ofertasSlider.querySelector('.swiper-button-next'),
                prevEl: ofertasSlider.querySelector('.swiper-button-prev')
            }
        });
    }
});