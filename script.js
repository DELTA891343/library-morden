// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    offset: 100,
    once: true
});

// Mobile Menu Toggle
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navLinks = document.querySelector('.nav-links');
const authButtons = document.querySelector('.auth-buttons');

mobileMenuBtn.addEventListener('click', () => {
    const spans = mobileMenuBtn.querySelectorAll('span');
    spans.forEach(span => span.classList.toggle('active'));
    
    // Create mobile menu if it doesn't exist
    if (!document.querySelector('.mobile-menu')) {
        const mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu';
        mobileMenu.innerHTML = `
            <div class="mobile-nav-links">
                ${navLinks.innerHTML}
            </div>
            <div class="mobile-auth-buttons">
                ${authButtons.innerHTML}
            </div>
        `;
        document.body.appendChild(mobileMenu);
        
        // Add styles for mobile menu
        const style = document.createElement('style');
        style.textContent = `
            .mobile-menu {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: white;
                padding: 2rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transform: translateY(-100%);
                transition: transform 0.3s ease;
                z-index: 999;
            }
            
            .mobile-menu.active {
                transform: translateY(0);
            }
            
            .mobile-nav-links {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-bottom: 2rem;
            }
            
            .mobile-auth-buttons {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }
            
            .mobile-menu-btn span.active:nth-child(1) {
                transform: translateY(6px) rotate(45deg);
            }
            
            .mobile-menu-btn span.active:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-btn span.active:nth-child(3) {
                transform: translateY(-6px) rotate(-45deg);
            }
        `;
        document.head.appendChild(style);
    }
    
    const mobileMenu = document.querySelector('.mobile-menu');
    mobileMenu.classList.toggle('active');
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            // Close mobile menu if open
            const mobileMenu = document.querySelector('.mobile-menu');
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                mobileMenuBtn.querySelectorAll('span').forEach(span => span.classList.remove('active'));
            }
        }
    });
});

// Navbar scroll effect
const navbar = document.querySelector('.navbar');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        navbar.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
        return;
    }
    
    if (currentScroll > lastScroll) {
        // Scrolling down
        navbar.style.transform = 'translateY(-100%)';
    } else {
        // Scrolling up
        navbar.style.transform = 'translateY(0)';
        navbar.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});