/**
 * Drivin - Driving School Website
 * Main JavaScript File
 */

$(document).ready(function () {
    // ======= Hero Slider =======
    let currentSlide = 0;
    const heroSlides = [
        {
            image: "assets/images/hero-bg.jpg",
            title: "Learn To Drive With Confidence",
            subtitle: "Professional driving instruction for all skill levels"
        },
        {
            image: "assets/images/hero-bg.jpg",
            title: "Pass Your Test First Time",
            subtitle: "Our students have a 90% first-time pass rate"
        },
        {
            image: "assets/images/hero-bg.jpg",
            title: "Expert Driving Instructors",
            subtitle: "Learn from certified professionals with years of experience"
        }
    ];

    // ======= Testimonial Slider =======
    window.currentTestimonial = 0;
    
    // ======= All Initialization Functions =======
    
    function initHeroSlider() {
        updateHeroSlide();

        $('.slider-arrow.prev').click(function () {
            currentSlide = (currentSlide > 0) ? currentSlide - 1 : heroSlides.length - 1;
            updateHeroSlide();
        });

        $('.slider-arrow.next').click(function () {
            currentSlide = (currentSlide < heroSlides.length - 1) ? currentSlide + 1 : 0;
            updateHeroSlide();
        });

        setInterval(function () {
            currentSlide = (currentSlide < heroSlides.length - 1) ? currentSlide + 1 : 0;
            updateHeroSlide();
        }, 8000);
    }

    function updateHeroSlide() {
        const slide = heroSlides[currentSlide];
        $('.hero').css('background-image', `url(${slide.image})`);

        $('.hero-content h1').fadeOut(400, function () {
            $(this).text(slide.title).fadeIn(400);
        });

        if ($('.hero-content p').length === 0) {
            $('<p class="hero-subtitle"></p>').insertAfter('.hero-content h1');
        }

        $('.hero-content p').fadeOut(400, function () {
            $(this).text(slide.subtitle).fadeIn(400);
        });
    }

    function initTestimonialSlider() {
        // Total number of testimonials
        const testimonialCount = $('.testimonial-item').length;
        
        // Show first testimonial
        $('#testimonial-0').show().addClass('active');
        
        // Set up click events for navigation
        $('.testimonial-controls .slider-arrow.prev').click(function() {
            navigateTestimonial('prev');
        });
        
        $('.testimonial-controls .slider-arrow.next').click(function() {
            navigateTestimonial('next');
        });
        
        // Set up click events for dots
        $('.dot').click(function() {
            const index = $(this).data('index');
            if (index !== window.currentTestimonial) {
                navigateToTestimonial(index);
            }
        });
        
        // Auto-rotate testimonials
        setInterval(function() {
            navigateTestimonial('next');
        }, 10000); // Change testimonial every 10 seconds
        
        // Initialize like feature if it exists
        if (typeof window.initLikeFeature === 'function') {
            window.initLikeFeature();
        }
        
        // Function to navigate to previous or next testimonial
        function navigateTestimonial(direction) {
            let newIndex;
            
            if (direction === 'prev') {
                newIndex = (window.currentTestimonial > 0) ? window.currentTestimonial - 1 : testimonialCount - 1;
            } else {
                newIndex = (window.currentTestimonial < testimonialCount - 1) ? window.currentTestimonial + 1 : 0;
            }
            
            navigateToTestimonial(newIndex);
        }
        
        // Function to navigate to specific testimonial by index
        function navigateToTestimonial(index) {
            // Hide current testimonial
            $(`#testimonial-${window.currentTestimonial}`).removeClass('active').fadeOut(400, function() {
                // Update current index
                window.currentTestimonial = index;
                
                // Show new testimonial
                $(`#testimonial-${window.currentTestimonial}`).fadeIn(400).addClass('active');
                
                // Update dots
                $('.dot').removeClass('active');
                $(`.dot[data-index="${window.currentTestimonial}"]`).addClass('active');
                
                // If testimonial like feature is integrated, update it
                if (window.updateTestimonial) {
                    window.updateTestimonial();
                }
            });
        }
    }
    
    // Function to update testimonial (accessible from outside)
    window.updateTestimonial = function() {
        // This function is empty by default and will be extended
        // by the testimonial like feature if it's included
    };

    function initCourseCards() {
        $('.course-card').hover(
            function () {
                $(this).find('.course-overlay').fadeIn(300);
            },
            function () {
                $(this).find('.course-overlay').fadeOut(300);
            }
        );
    }

    function initTeamMembers() {
        $('.team-member, .team-card').hover(
            function () {
                $(this).find('.social-links, .team-social').fadeIn(300);
            },
            function () {
                $(this).find('.social-links, .team-social').fadeOut(300);
            }
        );
    }

    function initStickyHeader() {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.header').addClass('sticky');
                $('.back-to-top').fadeIn();
            } else {
                $('.header').removeClass('sticky');
                $('.back-to-top').fadeOut();
            }
        });
    }

    function initBackToTop() {
        $('.back-to-top').click(function (e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 800);
            return false;
        });
    }

    function initMobileMenu() {
        $('.mobile-menu-toggle').click(function () {
            $('.nav-menu').toggleClass('active');
            $(this).toggleClass('active');
        });

        $(document).click(function (e) {
            if (!$(e.target).closest('.nav-menu, .mobile-menu-toggle').length) {
                $('.nav-menu').removeClass('active');
                $('.mobile-menu-toggle').removeClass('active');
            }
        });
    }

    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').click(function () {
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                location.hostname == this.hostname
            ) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 800);
                    return false;
                }
            }
        });
    }

    function initFormValidation() {
        // Form validation
        $('.appointment-form').on('submit', function(e) {
            let isValid = true;
            const form = this;

            $(form).find('input[required], textarea[required]').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).css('borderColor', '#ff3860');
                    isValid = false;
                } else {
                    $(this).css('borderColor', '');
                }
            });

            const emailField = $(form).find('input[type="email"]');
            if (emailField.val().trim() !== '') {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.val().trim())) {
                    emailField.css('borderColor', '#ff3860');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });

        // Remove error styling on focus
        $('.appointment-form input, .appointment-form textarea').on('focus', function() {
            $(this).css('borderColor', '');
        });
    }

    function initImageEffects() {
        $('.about-img, .about-images, .why-us-images, .why-us-image').hover(
            function () {
                $(this).find('.small-img, .small-image').addClass('active');
            },
            function () {
                $(this).find('.small-img, .small-image').removeClass('active');
            }
        );
    }

    function injectStyles() {
        // Dynamically inject hero font color styles
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .hero-content h1 {
                    color: #ffffff;
                }
                .hero-content p {
                    color: #f8f9fa;
                }
            `)
            .appendTo('head');
    }

    // ======= Initialize All Functions =======
    function initAll() {
        initHeroSlider();
        initTestimonialSlider();
        initCourseCards();
        initTeamMembers();
        initStickyHeader();
        initBackToTop();
        initMobileMenu();
        initSmoothScroll();
        initFormValidation();
        initImageEffects();
        injectStyles();
    }

    // Run everything
    initAll();
});