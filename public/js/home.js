// ===== Loader =====

    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            // Memberikan efek fade out
            loader.style.transition = 'opacity 0.5s ease';
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500); // Sesuaikan dengan durasi transition
        }
    });

        // Navbar shadow and colors on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            const logoText = document.getElementById('logo-text');
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const navLinks = document.querySelectorAll('.nav-link');
            
            if (window.scrollY > 20) {
                // Scrolled state: white background, dark texts
                nav.classList.add('nav-blur', 'bg-white/95', 'border-slate-200');
                nav.classList.remove('bg-transparent', 'border-transparent');
                nav.style.boxShadow = '0 4px 24px rgba(0,0,0,0.08)';
                
                if (logoText) {
                    logoText.classList.remove('text-white');
                    logoText.classList.add('text-slate-900');
                }
                if (mobileBtn) {
                    mobileBtn.classList.remove('text-white');
                    mobileBtn.classList.add('text-slate-700');
                }
                navLinks.forEach(link => {
                    link.classList.remove('text-white', 'border-white/20', 'hover:bg-white/20');
                    link.classList.add('text-slate-700', 'border-slate-200', 'hover:bg-slate-100');
                });
            } else {
                // Top state: transparent background, white texts
                nav.classList.remove('nav-blur', 'bg-white/95', 'border-slate-200');
                nav.classList.add('bg-transparent', 'border-transparent');
                nav.style.boxShadow = 'none';
                
                if (logoText) {
                    logoText.classList.remove('text-slate-900');
                    logoText.classList.add('text-white');
                }
                if (mobileBtn) {
                    mobileBtn.classList.remove('text-slate-700');
                    mobileBtn.classList.add('text-white');
                }
                navLinks.forEach(link => {
                    link.classList.remove('text-slate-700', 'border-slate-200', 'hover:bg-slate-100');
                    link.classList.add('text-white', 'border-white/20', 'hover:bg-white/20');
                });
            }
        });

        // Mobile menu toggle
        const btnMenu = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (btnMenu && mobileMenu) {
            btnMenu.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
            });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const el = document.querySelector(a.getAttribute('href'));
                if (el) el.scrollIntoView({ behavior: 'smooth' });
                // Hide mobile menu on link click
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('flex');
                }
            });
        });

        // Background Slider Logic
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slider-bg');
        const dotsContainer = document.getElementById('slider-dots');
        const dots = dotsContainer ? dotsContainer.querySelectorAll('button') : [];
        let slideInterval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
                if (dots[i]) {
                    dots[i].classList.remove('opacity-100');
                    dots[i].classList.add('opacity-50');
                }
            });
            if(slides[index]){
                slides[index].classList.remove('opacity-0');
                slides[index].classList.add('opacity-100');
            }
            if (dots[index]) {
                dots[index].classList.remove('opacity-50');
                dots[index].classList.add('opacity-100');
            }
            currentSlide = index;
        }

        function nextSlide() {
            if(slides.length === 0) return;
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        window.setSlide = function(index) {
            showSlide(index);
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 7000);
        };
        
        if (slides.length > 0) {
            slideInterval = setInterval(nextSlide, 7000);
        }