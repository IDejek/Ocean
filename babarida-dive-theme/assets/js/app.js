/**
 * Babarida Dive Center - Core Frontend JavaScript
 * Vanilla JS (ES6+), Zero jQuery Dependency
 * 
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 * @author Iqbal Tombinawa
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const BabaridaApp = {
        
        // Cache DOM elements safely
        dom: {
            preloader: document.getElementById('babarida-preloader'),
            mainHeader: document.getElementById('main-header'),
            topBar: document.getElementById('top-bar'),
            mobileToggle: document.getElementById('mobile-menu-toggle'),
            mobilePanel: document.getElementById('mobile-menu-panel'),
            mobileClose: document.getElementById('mobile-menu-close'),
            mobileBody: document.getElementById('mobile-menu-body'),
            backToTop: document.getElementById('floating-back-top'),
            newsletterForm: document.getElementById('newsletter-form'),
            newsletterMsg: document.getElementById('newsletter-message'),
            weatherGrid: document.getElementById('weather-grid'),
            partnersTrack: document.getElementById('partners-track'),
            animElements: document.querySelectorAll('[data-animate]'),
            magneticBtns: document.querySelectorAll('.btn-magnetic'),
            clocksDesktop: document.querySelector('.clocks-desktop'),
            clocksMobile: document.querySelector('.clocks-mobile'),
            testimonialTrack: document.querySelector('.testimonials-track'),
            testimonialPrev: document.querySelector('.testimonial-prev'),
            testimonialNext: document.querySelector('.testimonial-next'),
        },

        // State variables
        state: {
            isMenuOpen: false,
            isHeaderScrolled: false,
            currentTestimonial: 0,
            testimonialCount: 0,
            clockInterval: null,
        },

        // Initialize all modules
        init() {
            this.initPreloader();
            this.initWorldClocks();
            this.initHeaderScroll();
            this.initMobileMenu();
            this.initScrollAnimations();
            this.initBackToTop();
            this.initMagneticButtons();
            this.initTestimonials();
            this.initNewsletter();
            this.initWeather();
            this.initPartnersCarousel();
        },

        // ─── PRELOADER ────────────────────────────────
        initPreloader() {
            if (!this.dom.preloader) return;

            window.addEventListener('load', () => {
                setTimeout(() => {
                    this.dom.preloader.classList.add('loaded');
                    document.body.style.overflow = '';
                    // Trigger initial scroll animations
                    this.checkAnimations();
                }, 2200); // Match CSS animation duration
            });

            // Failsafe: remove preloader after 5s even if assets fail
            setTimeout(() => {
                this.dom.preloader.classList.add('loaded');
                document.body.style.overflow = '';
            }, 5000);
        },

        // ─── WORLD CLOCKS ─────────────────────────────
        initWorldClocks() {
            if (!this.dom.clocksDesktop && !this.dom.clocksMobile) return;

            const updateClocks = (container) => {
                if (!container) return;
                const items = container.querySelectorAll('.clock-item');
                items.forEach(item => {
                    const tz = item.getAttribute('data-tz');
                    if (!tz) return;
                    try {
                        const timeStr = new Intl.DateTimeFormat('en-GB', {
                            timeZone: tz,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        }).format(new Date());
                        
                        const timeEl = item.querySelector('.clock-time');
                        if (timeEl) timeEl.textContent = timeStr;
                    } catch (e) {
                        console.warn(`Timezone error for ${tz}:`, e);
                    }
                });
            };

            const tick = () => {
                updateClocks(this.dom.clocksDesktop);
                updateClocks(this.dom.clocksMobile);
            };

            tick(); // Run immediately
            this.state.clockInterval = setInterval(tick, 1000);
        },

        // ─── HEADER GLASSMORPHISM SCROLL ──────────────
        initHeaderScroll() {
            if (!this.dom.mainHeader) return;

            const handleScroll = () => {
                const scrollY = window.scrollY || window.pageYOffset;
                const triggerPoint = 50;

                if (scrollY > triggerPoint && !this.state.isHeaderScrolled) {
                    this.dom.mainHeader.classList.add('scrolled');
                    this.state.isHeaderScrolled = true;
                } else if (scrollY <= triggerPoint && this.state.isHeaderScrolled) {
                    this.dom.mainHeader.classList.remove('scrolled');
                    this.state.isHeaderScrolled = false;
                }
            };

            window.addEventListener('scroll', handleScroll, { passive: true });
            handleScroll(); // Check initial state
        },

        // ─── MOBILE MENU ──────────────────────────────
        initMobileMenu() {
            if (!this.dom.mobileToggle || !this.dom.mobilePanel) return;

            // Toggle Main Menu
            this.dom.mobileToggle.addEventListener('click', () => {
                this.toggleMobileMenu(true);
            });

            // Close Menu
            if (this.dom.mobileClose) {
                this.dom.mobileClose.addEventListener('click', () => {
                    this.toggleMobileMenu(false);
                });
            }

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (this.state.isMenuOpen && !this.dom.mobilePanel.contains(e.target) && !this.dom.mobileToggle.contains(e.target)) {
                    this.toggleMobileMenu(false);
                }
            });

            // Accordion Submenus
            if (this.dom.mobileBody) {
                const toggleBtns = this.dom.mobileBody.querySelectorAll('.mobile-submenu-toggle');
                toggleBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        const submenu = btn.closest('li').querySelector('.mobile-submenu');
                        if (submenu) {
                            const isOpen = submenu.classList.contains('open');
                            // Close all others
                            this.dom.mobileBody.querySelectorAll('.mobile-submenu.open').forEach(sm => sm.classList.remove('open'));
                            this.dom.mobileBody.querySelectorAll('.mobile-submenu-toggle.open').forEach(tb => tb.classList.remove('open'));
                            
                            if (!isOpen) {
                                submenu.classList.add('open');
                                btn.classList.add('open');
                            }
                        }
                    });
                });
            }

            // Handle ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.state.isMenuOpen) {
                    this.toggleMobileMenu(false);
                }
            });
        },

        toggleMobileMenu(open) {
            this.state.isMenuOpen = open;
            this.dom.mobilePanel.classList.toggle('open', open);
            this.dom.mobilePanel.setAttribute('aria-hidden', !open);
            this.dom.mobileToggle.classList.toggle('active', open);
            this.dom.mobileToggle.setAttribute('aria-expanded', open);
            document.body.style.overflow = open ? 'hidden' : '';
        },

        // ─── SCROLL ANIMATIONS (Intersection Observer) ─
        initScrollAnimations() {
            if (!this.dom.animElements.length) return;

            if (!('IntersectionObserver' in window)) {
                // Fallback for old browsers
                this.dom.animElements.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
            };

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-delay') || 0;
                        setTimeout(() => {
                            entry.target.classList.add('is-visible');
                        }, parseInt(delay, 10));
                        this.observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            this.dom.animElements.forEach(el => this.observer.observe(el));
        },

        checkAnimations() {
            // Force check for elements already in viewport on load
            if (this.observer) {
                this.dom.animElements.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        el.classList.add('is-visible');
                        this.observer.unobserve(el);
                    }
                });
            }
        },

        // ─── BACK TO TOP ──────────────────────────────
        initBackToTop() {
            if (!this.dom.backToTop) return;

            window.addEventListener('scroll', () => {
                if (window.scrollY > 600) {
                    this.dom.backToTop.style.opacity = '1';
                    this.dom.backToTop.style.visibility = 'visible';
                } else {
                    this.dom.backToTop.style.opacity = '0';
                    this.dom.backToTop.style.visibility = 'hidden';
                }
            }, { passive: true });

            this.dom.backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        // ─── MAGNETIC BUTTONS ─────────────────────────
        initMagneticButtons() {
            if (!this.dom.magneticBtns.length || window.matchMedia('(pointer: coarse)').matches) return; // Disable on touch

            this.dom.magneticBtns.forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    
                    // Cap movement to 8px
                    const moveX = x * 0.2;
                    const moveY = y * 0.2;
                    
                    btn.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });

                btn.addEventListener('mouseleave', () => {
                    btn.style.transform = 'translate(0, 0)';
                    btn.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    setTimeout(() => {
                        btn.style.transition = '';
                    }, 300);
                });
            });
        },

        // ─── TESTIMONIALS SLIDER ──────────────────────
        initTestimonials() {
            if (!this.dom.testimonialTrack) return;

            const cards = this.dom.testimonialTrack.querySelectorAll('.testimonial-card');
            this.state.testimonialCount = cards.length;
            if (this.state.testimonialCount === 0) return;

            // Duplicate cards for infinite loop effect
            const cloneCount = Math.min(this.state.testimonialCount, 6);
            for (let i = 0; i < cloneCount; i++) {
                const clone = cards[i % this.state.testimonialCount].cloneNode(true);
                this.dom.testimonialTrack.appendChild(clone);
            }

            const getVisibleCount = () => {
                if (window.innerWidth >= 1024) return 3;
                if (window.innerWidth >= 768) return 2;
                return 1;
            };

            const updateSlider = () => {
                const visibleCount = getVisibleCount();
                const cardWidth = this.dom.testimonialTrack.parentElement.offsetWidth / visibleCount;
                const offset = this.state.currentTestimonial * cardWidth;
                this.dom.testimonialTrack.style.transform = `translateX(-${offset}px)`;
            };

            if (this.dom.testimonialNext) {
                this.dom.testimonialNext.addEventListener('click', () => {
                    this.state.currentTestimonial++;
                    if (this.state.currentTestimonial >= this.state.testimonialCount) {
                        this.state.currentTestimonial = 0; // Reset seamlessly
                    }
                    updateSlider();
                });
            }

            if (this.dom.testimonialPrev) {
                this.dom.testimonialPrev.addEventListener('click', () => {
                    this.state.currentTestimonial--;
                    if (this.state.currentTestimonial < 0) {
                        this.state.currentTestimonial = this.state.testimonialCount - 1;
                    }
                    updateSlider();
                });
            }

            // Auto-play
            let autoPlay = setInterval(() => {
                if (this.dom.testimonialNext) this.dom.testimonialNext.click();
            }, 5000);

            // Pause on hover
            this.dom.testimonialTrack.addEventListener('mouseenter', () => clearInterval(autoPlay));
            this.dom.testimonialTrack.addEventListener('mouseleave', () => {
                autoPlay = setInterval(() => {
                    if (this.dom.testimonialNext) this.dom.testimonialNext.click();
                }, 5000);
            });

            // Handle resize
            window.addEventListener('resize', updateSlider);
            updateSlider();
        },

        // ─── NEWSLETTER AJAX ──────────────────────────
        initNewsletter() {
            if (!this.dom.newsletterForm) return;

            this.dom.newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                if (!this.dom.newsletterMsg) return;

                const formData = new FormData(this.dom.newsletterForm);
                formData.append('action', 'babarida_newsletter_subscribe');
                formData.append('email', formData.get('newsletter_email'));
                formData.append('first_name', formData.get('newsletter_first_name'));
                
                // Clean up extra appended fields
                formData.delete('newsletter_email');
                formData.delete('newsletter_first_name');

                this.dom.newsletterMsg.textContent = window.babaridaData.i18n.loading;
                this.dom.newsletterMsg.className = 'newsletter-message';

                fetch(window.babaridaData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        this.dom.newsletterMsg.textContent = response.data.message;
                        this.dom.newsletterMsg.className = 'newsletter-message success';
                        this.dom.newsletterForm.reset();
                    } else {
                        this.dom.newsletterMsg.textContent = response.data.message;
                        this.dom.newsletterMsg.className = 'newsletter-message error';
                    }
                })
                .catch(() => {
                    this.dom.newsletterMsg.textContent = window.babaridaData.i18n.error;
                    this.dom.newsletterMsg.className = 'newsletter-message error';
                });
            });
        },

        // ─── WEATHER WIDGET ───────────────────────────
        initWeather() {
            if (!this.dom.weatherGrid) return;

            const fetchWeather = () => {
                const formData = new FormData();
                formData.append('action', 'babarida_get_weather');
                formData.append('nonce', window.babaridaData.nonce);

                fetch(window.babaridaData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        this.renderWeather(response.data);
                    } else {
                        this.renderFallbackWeather();
                    }
                })
                .catch(() => this.renderFallbackWeather());
            };

            fetchWeather();
            // Refresh every 30 minutes
            setInterval(fetchWeather, 30 * 60 * 1000);
        },

        renderWeather(locations) {
            if (!this.dom.weatherGrid) return;
            
            // If it's a single location (legacy API call), wrap it
            const locArray = Array.isArray(locations) ? locations : [locations];

            let html = '';
            const iconMap = {
                '01d': '☀️', '01n': '🌙', '02d': '⛅', '02n': '☁️',
                '03d': '☁️', '03n': '☁️', '04d': '☁️', '04n': '☁️',
                '09d': '🌧️', '09n': '🌧️', '10d': '🌦️', '10n': '🌧️',
                '11d': '⛈️', '11n': '⛈️', '13d': '❄️', '13n': '❄️',
                '50d': '🌫️', '50n': '🌫️'
            };

            locArray.forEach(loc => {
                if (!loc) return;
                const icon = iconMap[loc.icon] || '🌊';
                html += `
                    <div class="weather-card" data-animate="fade-up">
                        <h3 class="weather-card-title">${loc.location || 'Bunaken'}</h3>
                        <div class="weather-icon">${icon}</div>
                        <div class="weather-temp">${loc.temp}°C</div>
                        <div class="weather-desc">${loc.description || 'Clear'}</div>
                        <div class="weather-details">
                            <span>💧 ${loc.humidity}%</span>
                            <span>💨 ${loc.wind_speed} kn</span>
                            <span>👁 ${loc.visibility} km</span>
                            <span>🌡 ${loc.feels_like}°C</span>
                        </div>
                    </div>
                `;
            });

            this.dom.weatherGrid.innerHTML = html;
            this.checkAnimations(); // Trigger animation for new elements
        },

        renderFallbackWeather() {
            if (!this.dom.weatherGrid) return;
            const mockData = [
                { location: 'Bunaken', temp: 29, feels_like: 32, humidity: 78, wind_speed: 8.5, description: 'scattered clouds', visibility: 10, icon: '02d' },
                { location: 'Lembeh Strait', temp: 29, feels_like: 31, humidity: 82, wind_speed: 6.2, description: 'few clouds', visibility: 12, icon: '02d' },
                { location: 'Bangka Island', temp: 28, feels_like: 31, humidity: 80, wind_speed: 10.1, description: 'clear sky', visibility: 15, icon: '01d' }
            ];
            this.renderWeather(mockData);
        },

        // ─── PARTNERS CAROUSEL ───────────────────────
        initPartnersCarousel() {
            if (!this.dom.partnersTrack) return;

            // Duplicate items for infinite scroll effect
            const items = this.dom.partnersTrack.innerHTML;
            if (items.trim()) {
                this.dom.partnersTrack.innerHTML = items + items;
            }
        }
    };

    // Launch Application
    BabaridaApp.init();
});
