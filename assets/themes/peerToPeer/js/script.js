// active nav item
const navItem = document.getElementsByClassName("nav-link");
for (const element of navItem) {
   element.addEventListener("click", () => {
      for (const ele of navItem) {
         ele.classList.remove("active");
      }
      element.classList.add("active");
   });
}

// input file preview
const previewImage = (id) => {
   document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

// toggle sidebar
const toggleSidebar = (id) => {
   const element = document.getElementById(id);
   element.classList.toggle("active");
};
const removeClass = (id) => {
   const element = document.getElementById(id);
   element.classList.remove("active");
};

$(document).ready(function () {
   $(".js-example-basic-single").select2();

   // owl carousel
   $(".testimonials").owlCarousel({
      loop: true,
      margin: 25,
      nav: false,
      dots: true,
      autoplay: false,
      autoplayTimeout: 3000,
      responsive: {
         0: {
            items: 1,
         },
         768: {
            items: 1,
         },
         992: {
            items: 2,
         },
      },
   });

   $(".gateways").owlCarousel({
      loop: true,
      margin: 25,
      nav: false,
      dots: true,
      autoplay: true,
      rtl: true,
      autoplayTimeout: 3000,
      responsive: {
         0: {
            items: 3,
         },
         768: {
            items: 5,
         },
         992: {
            items: 8,
         },
      },
   });

   $("#shareBlock").socialSharingPlugin({
      urlShare: window.location.href,
      description: $("meta[name=description]").attr("content"),
      title: $("title").text(),
   });

   // AOS ANIMATION
   AOS.init();

   // COUNTER UP
   // $(".counter").counterUp({
   //    delay: 10,
   //    time: 3000,
   // });

   // SCROLL TOP
   $(".scroll-up").fadeOut();
   $(window).scroll(function () {
      if ($(this).scrollTop() > 100) {
         $(".scroll-up").fadeIn();
      } else {
         $(".scroll-up").fadeOut();
      }
   });
});

const toggleSideMenu = () => {
   document.getElementById("sidebar").classList.toggle("active");
   document.getElementById("content").classList.toggle("active");
};
const hideSidebar = () => {
   document.getElementById("formWrapper").classList.remove("active");
   document.getElementById("formWrapper2").classList.remove("active");
};
const toggleClass = () => {
   document.getElementById("formWrapper").classList.add("active");
};

// Customize Fancybox
Fancybox.bind('[data-fancybox="gallery"]', {
   Carousel: {
      on: {
         change: (that) => {
            mainCarousel.slideTo(mainCarousel.findPageForSlide(that.page), {
               friction: 0,
            });
         },
      },
   },
});
