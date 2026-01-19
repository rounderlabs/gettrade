// Main js file
(function ($) {
  ("use strict");
  // Preloader
  jQuery(window).on("load", function () {
    $(".preloader").delay(1600).fadeOut("slow");
  });

  $(".sidebar-button").on("click", function () {
    $(this).toggleClass("active");
  });

  document
    .querySelector(".sidebar-button")
    .addEventListener("click", () =>
      document.querySelector(".main-menu").classList.toggle("show-menu")
    );

  $(".menu-close-btn").on("click", function () {
    $(".main-menu").removeClass("show-menu");
  });

	jQuery('.dropdown-icon').on('click', function () {
		jQuery(this).toggleClass('active').next('ul').slideToggle();
		jQuery(this).parent().siblings().children('ul').slideUp();
		jQuery(this).parent().siblings().children('.active').removeClass('active');
	});

	jQuery('.dropdown-icon2').on('click', function () {
		jQuery(this).toggleClass('active').next('.submenu-list').slideToggle();
		jQuery(this).parent().siblings().children('.submenu-list').slideUp();
		jQuery(this).parent().siblings().children('.active').removeClass('active');
	});	

  $(window).on("load", function () {
    titleAnimation();
});


// text animation start

  function titleAnimation() {
    const visibleSlowlyRight = document.querySelectorAll('.sec-title, .title-anim');

    const setInitialStyles = (chars, animationType) => {
        chars.forEach((char) => {
            char.style.display = 'inline-block';
            char.style.opacity = '0';
            char.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

            switch (animationType) {
                case 'slide-down':
                    char.style.transform = 'translateY(-20px)';
                    break;
                case 'rotate':
                    char.style.transform = 'rotate(-90deg)';
                    break;
                case 'zoom-in':
                    char.style.transform = 'scale(0)';
                    break;
                case 'fade-up':
                    char.style.transform = 'translateY(20px)';
                    break;
                case 'bounce-in':
                    char.style.transform = 'scale(0.5)';
                    break;
                case 'flip':
                    char.style.transform = 'rotateY(90deg)';
                    break;
                default: // slide-right
                    char.style.transform = 'translateX(20px)';
            }
        });
    };


    const revealChars = (element, animationType) => {
      const splitChar = new SplitType(element, {
          types: 'chars'
      });
      setInitialStyles(splitChar.chars, animationType);

      splitChar.chars.forEach((char, index) => {
          setTimeout(() => {
              char.style.opacity = '1';
              char.style.transform =
                  animationType === 'rotate' ? 'rotate(0deg)' :
                  animationType === 'zoom-in' ? 'scale(1)' :
                  animationType === 'fade-up' ? 'translateY(0)' :
                  animationType === 'bounce-in' ? 'scale(1)' :
                  animationType === 'flip' ? 'rotateY(0deg)' :
                  'translateX(0)';
          }, index * 30);
      });
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const animationType = entry.target.getAttribute('data-animation') || 'slide-right';
            revealChars(entry.target, animationType);
        } else {
            const splitChar = new SplitType(entry.target, {
                types: 'chars'
            });
            setInitialStyles(splitChar.chars, entry.target.getAttribute('data-animation') || 'slide-right');
        }
    });
}, {
    threshold: 0.1
});

visibleSlowlyRight.forEach((element) => {
    observer.observe(element);
});
}

// text animation end


  // Video Popup
  $('[data-fancybox="gallery"]').fancybox({
    buttons: [
      "close"
    ],
    loop: false,
    protect: true
  });
  $('.video-player').fancybox({
    buttons: [
      "close"
    ],
    loop: false,
    protect: true
  });

  //  counter
  $('.counter').counterUp({
    delay: 10,
    time: 1500
  });

  // niceSelect
  $(document).ready(function () {
    $('select').niceSelect();
  });


    // logo area marquee start
    if ($('.marquee_text').length > 0) {
      $('.marquee_text').marquee({
        direction: 'left',
        duration: 40000,
        gap: 10,
        delayBeforeStart: 0,
        duplicated: true,
        startVisible: true
      })
    }

        if ($('.marquee_text-two').length > 0) {
      $('.marquee_text-two').marquee({
        direction: 'left',
        duration: 100000,
        gap: 10,
        delayBeforeStart: 0,
        duplicated: true,
        startVisible: true
      })
    }
  // sticky header
  window.addEventListener('scroll', function () {
    const header = document.querySelector('header.header-area')
    header.classList.toggle('sticky', window.scrollY > 200)
  })
  // sticky header end

  function containerFull() {
    var container = document.querySelector('.container');
    var distanceFromWindow = container.offsetLeft;
    var containerStyles = window.getComputedStyle(container);
    var paddingOneSide = parseInt(containerStyles.paddingLeft)

    document.querySelectorAll('.container-fluid-sticky-right').forEach((el) => {
      el.style.marginLeft = distanceFromWindow + 'px';
      el.style.paddingLeft = paddingOneSide + 'px';
    });
  }
  containerFull();
  window.addEventListener('resize', containerFull);






  // scroll up button
  document.addEventListener('DOMContentLoaded', function (event) {
    let offset = 50
    let circleContainer = document.querySelector('.circle-container')
    let circlePath = document.querySelector('.circle-container path')
    let pathLength = circlePath.getTotalLength()
    circlePath.style.transition = circlePath.style.WebkitTransition = 'none'
    circlePath.style.strokeDasharray = pathLength
    circlePath.style.strokeDashoffset = pathLength
    circlePath.getBoundingClientRect()
    circlePath.style.transition = circlePath.style.WebkitTransition =
      'stroke-dashoffset 10ms linear'

    let updateLoader = () => {
      let scrollTop = window.scrollY
      let docHeight = document.body.offsetHeight
      let winHeight = window.innerHeight
      let height = docHeight - winHeight
      let progress = pathLength - (scrollTop * pathLength) / height
      circlePath.style.strokeDashoffset = progress

      if (scrollTop > offset) {
        circleContainer.classList.add('active')
      } else {
        circleContainer.classList.remove('active')
      }
    }
    circleContainer.onclick = function () {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }

    window.onscroll = () => {
      updateLoader()
    }
    updateLoader()
  })


  // work process image wrapper
  const serviceImgItem2 = document.querySelectorAll(
    '.order-wrap .single-order'
  )
  function __followImageCursor2(event, serviceImgItem2) {
    const contentBox = serviceImgItem2.getBoundingClientRect()
    const dx = event.clientX - contentBox.x
    const dy = event.clientY - contentBox.y
    serviceImgItem2.children[2].style.transform = `translate(${dx}px, ${dy}px)`
  }
  serviceImgItem2.forEach((item, i) => {
    item.addEventListener('mousemove', event => {
      setInterval(__followImageCursor2(event, item), 100)
    })
  })


  //Team Info Flow
	const infoTeam = document.querySelectorAll(".team-info-flow-card");
	function followImageCursor(event, infoTeam) {
	const contentBox = infoTeam.getBoundingClientRect();
	const dx = event.clientX - contentBox.x;
	const dy = event.clientY - contentBox.y;
	infoTeam.children[1].style.transform = `translate(${dx}px, ${dy}px)`;
	}

	infoTeam.forEach((item, i) => {
		item.addEventListener("mousemove", (event) => {
			setInterval(followImageCursor(event, item), 100);
		});
	});

  
	// Progrees Bar
var skillPers = document.querySelectorAll(".experience-bar-per");

skillPers.forEach(function (skillPer) {
    var per = parseFloat(skillPer.getAttribute("data-per"));
    skillPer.style.width = per + "%";

    var animatedValue = 0;
    var startTime = null;

    function animate(timestamp) {
        if (!startTime) startTime = timestamp;
        var progress = timestamp - startTime;
        var stepPercentage = Math.min(progress / 1000, 1); // Ensures it does not exceed 1

        animatedValue = per * stepPercentage;
        skillPer.setAttribute("data-per", Math.floor(animatedValue) + "%");

        if (stepPercentage < 1) {
            requestAnimationFrame(animate);
        }
    }

    requestAnimationFrame(animate);
});


  // swiper slider area

  var swiper = new Swiper(".banner-img-slider", {
		slidesPerView: 1,
		speed: 2500,
		spaceBetween: 25,
		effect: 'fade',             // Use the fade effect
		fadeEffect: {
		  crossFade: true,           // Enable cross-fade transition
		},
		autoplay: {
			delay: 2500, // Autoplay duration in milliseconds
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: ".home1-banner-next",
			prevEl: ".home1-banner-prev",
		},
	}); 

  var swiper = new Swiper(".home2-banner-slider", {
		slidesPerView: 1,
		speed: 2500,
		spaceBetween: 25,
		effect: 'fade',             // Use the fade effect
		fadeEffect: {
		  crossFade: true,           // Enable cross-fade transition
		},
		autoplay: {
			delay: 2500, // Autoplay duration in milliseconds
			disableOnInteraction: false,
		},
		navigation: {
		nextEl: ".banner-slider-next",
		prevEl: ".banner-slider-prev",
		},
	});

  var swiper = new Swiper(".testimonial-card-slider", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 3.4,
      },
    }
  });

  var swiper = new Swiper(".testimonial-card-slider-two", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1.4,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 3.2,
      },
    }
  });

  var swiper = new Swiper(".teams-card-slider", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".team-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });
  
  var swiper = new Swiper(".service-card-slider", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".service-card-next",
      prevEl: ".service-card-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });

  var swiper = new Swiper(".service-card-slider-two", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".service-next",
      prevEl: ".service-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 3,
      },
    }
  });

	var swiper = new Swiper(".project-portfolio-slider", {
		slidesPerView: 1,
		speed: 1500,
		spaceBetween: 25,
		effect: 'fade',             // Use the fade effect
		fadeEffect: {
		  crossFade: true           // Enable cross-fade transition
		},
		autoplay: {
			delay: 1500, // Autoplay duration in milliseconds
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: ".project-slider-next",    
			prevEl: ".project-slider-prev",
		},
		pagination: {
			el: '.franctional-slider-pagi1',
			type: "fraction",
		},
	});

  var swiper = new Swiper(".testimonials-slider-three", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".team-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1.4,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 1.3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 1.6,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 2,
      },
    }
  })

  var swiper = new Swiper(".banner-img-slider-four", {
		slidesPerView: 1,
		speed: 2500,
		spaceBetween: 25,
		effect: 'fade',             // Use the fade effect
		fadeEffect: {
		  crossFade: true,           // Enable cross-fade transition
		},
		autoplay: {
			delay: 3000, // Autoplay duration in milliseconds
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: ".home1-banner-next",
			prevEl: ".home1-banner-prev",
		},
    pagination: {
			el: '.franctional-slider-pagi4',
			type: "fraction",
		},
	}); 

  var swiper = new Swiper(".solution-card-slider-four", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });

  var swiper = new Swiper(".testimonial-card-slider-four", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination-four",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1.4,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 3.2,
      },
    }
  });


  var swiper = new Swiper(".testimonial-card-slider-one", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination-four",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1.4,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 3.2,
      },
    }
  });

  var swiper = new Swiper(".solution-card-slider-five", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".solution-next",
      prevEl: ".solution-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 3,
      },
    }
  });

  var swiper = new Swiper(".landmark-card-slider-five", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".landmark-next",
      prevEl: ".landmark-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 3,
      },
    }
  });

  var swiper = new Swiper(".teams-card-slider-five", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".team-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });

  var swiper = new Swiper(".testimonial-card-slider-five", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-pagination",
      clickable: true,
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });

  var swiper = new Swiper(".service-card-slider-six", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".service-card-next",
      prevEl: ".service-card-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 4,
      },
    }
  });

  var swiper = new Swiper(".project-card-slider-six", {
    slidesPerView: 1,
    speed: 1500,
    spaceBetween: 25,
    autoplay: {
      delay: 1500, // Autoplay duration in milliseconds
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".project-next",
      prevEl: ".project-prev",
    },
    breakpoints: {
      280: {
        slidesPerView: 1,
      },
      386: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 1,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 15,
      },
      1400: {
        slidesPerView: 3,
      },
    }
  });



// magnetic-item style start

  if ($("body").not(".is-mobile").hasClass("tt-magic-cursor")) {
		if ($(window).width() > 1024) {
		  gsap.config({
			nullTargetWarn: false,
			trialWarn: false,
		  });
		  $(".magnetic-item").wrap('<div class="magnetic-wrap"></div>');
	
		  if ($("a.magnetic-item").length) {
			$("a.magnetic-item").addClass("not-hide-cursor");
		  }
	
		  var $mouse = { x: 0, y: 0 }; // Cursor position
		  var $pos = { x: 0, y: 0 }; // Cursor position
		  var $ratio = 0.15; // delay follow cursor
		  var $active = false;
		  var $ball = $("#ball");
	
		  var $ballWidth = 20; // Ball default width
		  var $ballHeight = 20; // Ball default height
		  var $ballOpacity = 0.5; // Ball default opacity
		  var $ballBorderWidth = 2; // Ball default border width
	
		  gsap.set($ball, {
			// scale from middle and style ball
			xPercent: -50,
			yPercent: -50,
			width: $ballWidth,
			height: $ballHeight,
			borderWidth: $ballBorderWidth,
			opacity: $ballOpacity,
		  });
	
		  document.addEventListener("mousemove", mouseMove);
	
		  function mouseMove(e) {
			$mouse.x = e.clientX;
			$mouse.y = e.clientY;
		  }
	
		  gsap.ticker.add(updatePosition);
	
		  function updatePosition() {
			if (!$active) {
			  $pos.x += ($mouse.x - $pos.x) * $ratio;
			  $pos.y += ($mouse.y - $pos.y) * $ratio;
	
			  gsap.set($ball, { x: $pos.x, y: $pos.y });
			}
		  }
	
		  $(".magnetic-wrap").mousemove(function (e) {
			parallaxCursor(e, this, 2); // magnetic ball = low number is more attractive
			callParallax(e, this);
		  });
	
		  function callParallax(e, parent) {
			parallaxIt(e, parent, parent.querySelector(".magnetic-item"), 25); // magnetic area = higher number is more attractive
		  }
	
		  function parallaxIt(e, parent, target, movement) {
			var boundingRect = parent.getBoundingClientRect();
			var relX = e.clientX - boundingRect.left;
			var relY = e.clientY - boundingRect.top;
	
			gsap.to(target, {
			  duration: 0.3,
			  x: ((relX - boundingRect.width / 2) / boundingRect.width) * movement,
			  y:
				((relY - boundingRect.height / 2) / boundingRect.height) * movement,
			  ease: Power2.easeOut,
			});
		  }
	
		  function parallaxCursor(e, parent, movement) {
			var rect = parent.getBoundingClientRect();
			var relX = e.clientX - rect.left;
			var relY = e.clientY - rect.top;
			$pos.x =
			  rect.left + rect.width / 2 + (relX - rect.width / 2) / movement;
			$pos.y =
			  rect.top + rect.height / 2 + (relY - rect.height / 2) / movement;
			gsap.to($ball, { duration: 0.3, x: $pos.x, y: $pos.y });
		  }
    }
  }

  // magnetic-item style end
  
  // ============== magic cursor start   ===================

	if ($("body").not(".is-mobile").hasClass("tt-magic-cursor")) {
		if ($(window).width() > 1024) {
		  gsap.config({
			nullTargetWarn: false,
			trialWarn: false,
		  });
		  $(".magnetic-item").wrap('<div class="magnetic-wrap"></div>');
	
		  if ($("a.magnetic-item").length) {
			$("a.magnetic-item").addClass("not-hide-cursor");
		  }
	
		  var $mouse = { x: 0, y: 0 }; // Cursor position
		  var $pos = { x: 0, y: 0 }; // Cursor position
		  var $ratio = 0.15; // delay follow cursor
		  var $active = false;
		  var $ball = $("#ball");
	
		  var $ballWidth = 20; // Ball default width
		  var $ballHeight = 20; // Ball default height
		  var $ballOpacity = 0.5; // Ball default opacity
		  var $ballBorderWidth = 2; // Ball default border width
	
		  gsap.set($ball, {
			// scale from middle and style ball
			xPercent: -50,
			yPercent: -50,
			width: $ballWidth,
			height: $ballHeight,
			borderWidth: $ballBorderWidth,
			opacity: $ballOpacity,
		  });
	
		  document.addEventListener("mousemove", mouseMove);
	
		  function mouseMove(e) {
			$mouse.x = e.clientX;
			$mouse.y = e.clientY;
		  }
	
		  gsap.ticker.add(updatePosition);
	
		  function updatePosition() {
			if (!$active) {
			  $pos.x += ($mouse.x - $pos.x) * $ratio;
			  $pos.y += ($mouse.y - $pos.y) * $ratio;
	
			  gsap.set($ball, { x: $pos.x, y: $pos.y });
			}
		  }
	
		  $(".magnetic-wrap").mousemove(function (e) {
			parallaxCursor(e, this, 2); // magnetic ball = low number is more attractive
			callParallax(e, this);
		  });
	
		  function callParallax(e, parent) {
			parallaxIt(e, parent, parent.querySelector(".magnetic-item"), 25); // magnetic area = higher number is more attractive
		  }
	
		  function parallaxIt(e, parent, target, movement) {
			var boundingRect = parent.getBoundingClientRect();
			var relX = e.clientX - boundingRect.left;
			var relY = e.clientY - boundingRect.top;
	
			gsap.to(target, {
			  duration: 0.3,
			  x: ((relX - boundingRect.width / 2) / boundingRect.width) * movement,
			  y:
				((relY - boundingRect.height / 2) / boundingRect.height) * movement,
			  ease: Power2.easeOut,
			});
		  }
	
		  function parallaxCursor(e, parent, movement) {
			var rect = parent.getBoundingClientRect();
			var relX = e.clientX - rect.left;
			var relY = e.clientY - rect.top;
			$pos.x =
			  rect.left + rect.width / 2 + (relX - rect.width / 2) / movement;
			$pos.y =
			  rect.top + rect.height / 2 + (relY - rect.height / 2) / movement;
			gsap.to($ball, { duration: 0.3, x: $pos.x, y: $pos.y });
		  }
	
		  // Magic cursor behavior
		  // ======================
	
		  // Magnetic item hover.
		  $(".magnetic-wrap")
			.on("mouseenter mouseover", function (e) {
			  $ball.addClass("magnetic-active");
			  gsap.to($ball, { duration: 0.3, width: 70, height: 70, opacity: 1 });
			  $active = true;
			})
			.on("mouseleave", function (e) {
			  $ball.removeClass("magnetic-active");
			  gsap.to($ball, {
				duration: 0.3,
				width: $ballWidth,
				height: $ballHeight,
				opacity: $ballOpacity,
			  });
			  gsap.to(this.querySelector(".magnetic-item"), {
				duration: 0.3,
				x: 0,
				y: 0,
				clearProps: "all",
			  });
			  $active = false;
			});
	
		  // Alternative cursor style on hover.
		  $(
			".cursor-alter, .tt-main-menu-list > li > a, .tt-main-menu-list > li > .tt-submenu-trigger > a"
		  )
			.not(".magnetic-item") // omit from selection.
			.on("mouseenter", function () {
			  gsap.to($ball, {
				duration: 0.3,
				borderWidth: 0,
				opacity: 0.2,
				backgroundColor: "#CCC",
				width: "90px",
				height: "90px",
			  });
			})
			.on("mouseleave", function () {
			  gsap.to($ball, {
				duration: 0.3,
				borderWidth: $ballBorderWidth,
				opacity: $ballOpacity,
				backgroundColor: "transparent",
				width: $ballWidth,
				height: $ballHeight,
				clearProps: "backgroundColor",
			  });
			});	
		
		  // Cursor view on hover (data attribute "data-cursor="...").
		  $("[data-cursor]").each(function () {
			$(this)
			  .on("mouseenter", function () {
				$ball
				  .addClass("ball-view")
				  .append('<div class="ball-view-inner"></div>');
				$(".ball-view-inner").append($(this).attr("data-cursor"));
				gsap.to($ball, {
				  duration: 0.3,
				  yPercent: -75,
				  width: 82,
				  height: 82,
				  opacity: 1,
				  borderWidth: 0,
				});
				gsap.to(".ball-view-inner", {
				  duration: 0.3,
				  scale: 1,
				  autoAlpha: 1,
				});
			  })
			  .on("mouseleave", function () {
				gsap.to($ball, {
				  duration: 0.3,
				  yPercent: -50,
				  width: $ballWidth,
				  height: $ballHeight,
				  opacity: $ballOpacity,
				  borderWidth: $ballBorderWidth,
				});
				$ball.removeClass("ball-view").find(".ball-view-inner").remove();
			  });
			$(this).addClass("not-hide-cursor");
		  });
	
		  // Cursor drag on hover (class "cursor-drag"). For Swiper sliders.
		  $(".swiper").each(function () {
			if ($(this).parent().attr("data-simulate-touch") === "true") {
			  if ($(this).parent().hasClass("cursor-drag")) {
				$(this)
				  .on("mouseenter", function () {
					$ball.append('<div class="ball-drag"></div>');
					gsap.to($ball, {
					  duration: 0.3,
					  width: 60,
					  height: 60,
					  opacity: 1,
					});
				  })
				  .on("mouseleave", function () {
					$ball.find(".ball-drag").remove();
					gsap.to($ball, {
					  duration: 0.3,
					  width: $ballWidth,
					  height: $ballHeight,
					  opacity: $ballOpacity,
					});
				  });
				$(this).addClass("not-hide-cursor");
	
				// Ignore "data-cursor" on hover.
				$(this)
				  .find("[data-cursor]")
				  .on("mouseenter mouseover", function () {
					$ball.find(".ball-drag").remove();
					return false;
				  })
				  .on("mouseleave", function () {
					$ball.append('<div class="ball-drag"></div>');
					gsap.to($ball, {
					  duration: 0.3,
					  width: 60,
					  height: 60,
					  opacity: 1,
					});
				  });
			  }
			}
		  });
	
		  // Cursor drag on mouse down / click and hold effect (class "cursor-drag-mouse-down"). For Swiper sliders.
		  $(".swiper").each(function () {
			if ($(this).parent().attr("data-simulate-touch") === "true") {
			  if ($(this).parent().hasClass("cursor-drag-mouse-down")) {
				$(this)
				  .on("mousedown pointerdown", function (e) {
					if (e.which === 1) {
					  // Affects the left mouse button only!
					  gsap.to($ball, {
						duration: 0.2,
						width: 60,
						height: 60,
						opacity: 1,
					  });
					  $ball.append('<div class="ball-drag"></div>');
					}
				  })
				  .on("mouseup pointerup", function () {
					$ball.find(".ball-drag").remove();
					if ($(this).find("[data-cursor]:hover").length) {
					} else {
					  gsap.to($ball, {
						duration: 0.2,
						width: $ballWidth,
						height: $ballHeight,
						opacity: $ballOpacity,
					  });
					}
				  })
				  .on("mouseleave", function () {
					$ball.find(".ball-drag").remove();
					gsap.to($ball, {
					  duration: 0.2,
					  width: $ballWidth,
					  height: $ballHeight,
					  opacity: $ballOpacity,
					});
				  });
	
				// Ignore "data-cursor" on mousedown.
				$(this)
				  .find("[data-cursor]")
				  .on("mousedown pointerdown", function () {
					return false;
				  });
	
				// Ignore "data-cursor" on hover.
				$(this)
				  .find("[data-cursor]")
				  .on("mouseenter mouseover", function () {
					$ball.find(".ball-drag").remove();
					return false;
				  });
			  }
			}
		  });
	
		  // Cursor close on hover.
		  $(".cursor-close").each(function () {
			$(this).addClass("ball-close-enabled");
			$(this)
			  .on("mouseenter", function () {
				$ball.addClass("ball-close-enabled");
				$ball.append('<div class="ball-close">Close</div>');
				gsap.to($ball, {
				  duration: 0.3,
				  yPercent: -75,
				  width: 80,
				  height: 80,
				  opacity: 1,
				});
				gsap.from(".ball-close", { duration: 0.3, scale: 0, autoAlpha: 0 });
			  })
			  .on("mouseleave click", function () {
				$ball.removeClass("ball-close-enabled");
				gsap.to($ball, {
				  duration: 0.3,
				  yPercent: -50,
				  width: $ballWidth,
				  height: $ballHeight,
				  opacity: $ballOpacity,
				});
				$ball.find(".ball-close").remove();
			  });
	
			// Hover on "cursor-close" inner elements.
			$(
			  ".cursor-close a, .cursor-close button, .cursor-close .tt-btn, .cursor-close .hide-cursor"
			)
			  .not(".not-hide-cursor") // omit from selection (class "not-hide-cursor" is for global use).
			  .on("mouseenter", function () {
				$ball.removeClass("ball-close-enabled");
			  })
			  .on("mouseleave", function () {
				$ball.addClass("ball-close-enabled");
			  });
		  });
	
		  // ================================================================
		  // Scroll between anchors
		  // ================================================================
	
		  $('a[href^="#"]')
			.not('[href$="#"]') // omit from selection
			.not('[href$="#0"]') // omit from selection
			.on("click", function () {
			  var target = this.hash;
	
			  // If fixed header position enabled.
			  if ($("#tt-header").hasClass("tt-header-fixed")) {
				var $offset = $("#tt-header").height();
			  } else {
				var $offset = 0;
			  }
	
			  // You can use data attribute (for example: data-offset="100") to set top offset in HTML markup if needed.
			  if ($(this).data("offset") != undefined)
				$offset = $(this).data("offset");
	
			  
			  return false;
			});
	
		 
		  // Show/hide magic cursor
		  // =======================
	
		  // Hide on hover.
		  $(
			"a, button, .tt-btn, .tt-form-control, .tt-form-radio, .tt-form-check, .hide-cursor"
		  ) // class "hide-cursor" is for global use.
			.not(".not-hide-cursor") // omit from selection (class "not-hide-cursor" is for global use).
			.not(".cursor-alter") // omit from selection
			.not(".tt-main-menu-list > li > a") // omit from selection
			.not(".tt-main-menu-list > li > .tt-submenu-trigger > a") // omit from selection
			.on("mouseenter", function () {
			  gsap.to($ball, { duration: 0.3, scale: 0, opacity: 0 });
			})
			.on("mouseleave", function () {
			  gsap.to($ball, { duration: 0.3, scale: 1, opacity: $ballOpacity });
			});
	
		  // Hide on click.
		  $("a")
			.not('[target="_blank"]') // omit from selection.
			.not('[href^="#"]') // omit from selection.
			.not('[href^="mailto"]') // omit from selection.
			.not('[href^="tel"]') // omit from selection.
			.not(".lg-trigger") // omit from selection.
			.not(".video-player") // omit from selection.
			.not(".tt-btn-disabled") // omit from selection.
			.on("click", function () {
			  gsap.to($ball, { duration: 0.3, scale: 1.3, autoAlpha: 0 });
			});

		  // Show/hide on document leave/enter.
		  $(document)
			.on("mouseleave", function () {
			  gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 0 });
			})
			.on("mouseenter", function () {
			  gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 1 });
			});
	
		  // Show as the mouse moves.
		  $(document).mousemove(function () {
			gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 1 });
	   	  });
		}
	}

   // ============== magic cursor end   ===================


  // progress line
  if ($('.progress-bar').length) {
    $(window).on('scroll', function() {
      let scroll = $(window).scrollTop();
      let oTop = $('.progress-bar').offset().top - window.innerHeight;
      if (scroll > oTop) {
        $(".progress-bar").addClass("progressbar-active");
      } else {
        $(".progress-bar").removeClass("progressbar-active");
      }
    });
  }
  
	//Quantity Increment
	$(".quantity__minus").on("click", function (e) {
		e.preventDefault();
		var input = $(this).siblings(".quantity__input");
		var value = parseInt(input.val(),10);
		if (value > 1) {
			value--;
		}
		input.val(value.toString().padStart(2, "0"));
	});
	$(".quantity__plus").on("click", function (e) {
		e.preventDefault();
		var input = $(this).siblings(".quantity__input");
		var value = parseInt(input.val(),10);
		value++;
		input.val(value.toString().padStart(2, "0"));
	});



// wow js
  $(window).on('load', function () {
    new WOW().init();
    window.wow = new WOW({
        boxClass: 'wow',
        animateClass: 'animated',
        offset: 0,
        mobile: true,
        live: true,
        offset: 80
    })
    window.wow.init();
  });

})(jQuery)
