/**
 * PT Jaringan Teknologi Sejahtera (JTS) — Frontend Entry Point
 * Stack: Alpine.js + GSAP + AOS + Lenis Smooth Scroll + Three.js (hero bg) + Swiper
 */

import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import Intersect from '@alpinejs/intersect';
import Persist from '@alpinejs/persist';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TextPlugin } from 'gsap/TextPlugin';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Lenis from 'lenis';

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

declare global {
    interface Window {
        Alpine: typeof Alpine;
        gsap: typeof gsap;
        lenis: Lenis;
    }
}

// ═══ 1. Alpine.js ═══
Alpine.plugin(Collapse);
Alpine.plugin(Intersect);
Alpine.plugin(Persist);
window.Alpine = Alpine;

// ═══ 2. GSAP Plugins ═══
gsap.registerPlugin(ScrollTrigger, TextPlugin);
window.gsap = gsap;

// ═══ 3. Lenis Smooth Scroll ═══
function initLenis(): void {
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t: number) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        wheelMultiplier: 1,
        touchMultiplier: 1.5,
    });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time: number) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
    window.lenis = lenis;

    document.querySelectorAll<HTMLAnchorElement>('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (!href || href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                lenis.scrollTo(target as HTMLElement, { offset: -80 });
            }
        });
    });
}

// ═══ 4. AOS ═══
function initAOS(): void {
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });
}

// ═══ 5. Custom Cursor (Desktop Only) ═══
function initCustomCursor(): void {
    if (window.innerWidth < 1024) return;

    const dot = document.getElementById('jts-cursor-dot');
    const ring = document.getElementById('jts-cursor-ring');
    const glowOrb = document.getElementById('jts-glow-orb');
    const mouseGlow = document.getElementById('jts-mouse-glow');

    if (!dot || !ring) return;

    let mouseX = 0, mouseY = 0;
    let ringX = 0, ringY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX; mouseY = e.clientY;
        gsap.to(dot, { x: mouseX, y: mouseY, duration: 0.05 });
        if (glowOrb && mouseGlow) {
            gsap.to(glowOrb, { x: mouseX, y: mouseY, duration: 0.3 });
            gsap.to(mouseGlow, { opacity: 1, duration: 0.3 });
        }
    });

    gsap.ticker.add(() => {
        ringX += (mouseX - ringX) * 0.12;
        ringY += (mouseY - ringY) * 0.12;
        gsap.set(ring, { x: ringX, y: ringY });
    });

    document.querySelectorAll<HTMLElement>('a, button, [data-cursor="pointer"]').forEach((el) => {
        el.addEventListener('mouseenter', () => {
            gsap.to(ring, { scale: 1.8, borderColor: 'rgba(255,102,0,0.8)', duration: 0.3 });
            gsap.to(dot, { scale: 0, duration: 0.3 });
        });
        el.addEventListener('mouseleave', () => {
            gsap.to(ring, { scale: 1, borderColor: 'rgba(255,102,0,0.5)', duration: 0.3 });
            gsap.to(dot, { scale: 1, duration: 0.3 });
        });
    });
}

// ═══ 6. Loading Screen ═══
function initLoadingScreen(): void {
    const loader = document.getElementById('jts-loading-screen');
    const bar = document.getElementById('jts-loading-bar');
    if (!loader || !bar) return;

    gsap.to(bar, {
        width: '100%', duration: 0.8, ease: 'power2.inOut',
        onComplete: () => {
            gsap.to(loader, {
                opacity: 0, duration: 0.5, delay: 0.2,
                onComplete: () => {
                    loader.style.display = 'none';
                    gsap.from('.hero-badge', { y: -20, opacity: 0, duration: 0.6, ease: 'power2.out' });
                    gsap.from('.hero-heading', { y: 40, opacity: 0, duration: 0.8, delay: 0.1, ease: 'expo.out' });
                    gsap.from('.hero-subtitle', { y: 30, opacity: 0, duration: 0.7, delay: 0.25, ease: 'expo.out' });
                },
            });
        },
    });
}

// ═══ 7. Three.js Hero Background (lazy loaded) ═══
async function initHeroCanvas(): Promise<void> {
    const canvas = document.getElementById('hero-canvas') as HTMLCanvasElement | null;
    if (!canvas) return;

    const THREE = await import('three');

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.position.z = 3;

    const particleCount = 180;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount; i++) {
        positions[i * 3] = (Math.random() - 0.5) * 12;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 10;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 6;
        colors[i * 3] = 1.0;
        colors[i * 3 + 1] = 0.2 + Math.random() * 0.3;
        colors[i * 3 + 2] = 0;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({ size: 0.025, vertexColors: true, transparent: true, opacity: 0.7 });
    const particles = new THREE.Points(geometry, material);
    scene.add(particles);

    let mouseX = 0, mouseY = 0;
    document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX / window.innerWidth - 0.5) * 0.5;
        mouseY = -(e.clientY / window.innerHeight - 0.5) * 0.5;
    });

    const clock = new THREE.Clock();
    function animate(): void {
        requestAnimationFrame(animate);
        const t = clock.getElapsedTime();
        particles.rotation.y = t * 0.04 + mouseX;
        particles.rotation.x = t * 0.02 + mouseY;
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}

// ═══ 8. Swiper Instances ═══
function initSwipers(): void {
    if (document.querySelector('.testimonial-swiper')) {
        new Swiper('.testimonial-swiper', {
            modules: [Pagination, Autoplay],
            slidesPerView: 1, spaceBetween: 20, loop: true,
            autoplay: { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true },
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
        });
    }

    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            modules: [Navigation, Pagination, Autoplay, EffectFade],
            effect: 'fade', loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.hero-swiper-pagination', clickable: true },
            navigation: { nextEl: '.hero-swiper-next', prevEl: '.hero-swiper-prev' },
        });
    }

    if (document.querySelector('.portfolio-swiper')) {
        new Swiper('.portfolio-swiper', {
            modules: [Pagination],
            slidesPerView: 1.2, spaceBetween: 16,
            pagination: { el: '.portfolio-swiper-pagination', clickable: true },
            breakpoints: { 480: { slidesPerView: 1.5 }, 768: { slidesPerView: 2.5 } },
        });
    }
}

// ═══ 9. Scroll to Top ═══
function initScrollToTop(): void {
    const btn = document.getElementById('jts-scroll-top');
    if (!btn) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            btn.style.opacity = '1'; btn.style.transform = 'translateY(0)';
        } else {
            btn.style.opacity = '0'; btn.style.transform = 'translateY(16px)';
        }
    }, { passive: true });
}

// ═══ 10. GSAP ScrollTrigger — Parallax & Reveal ═══
function initScrollAnimations(): void {
    document.querySelectorAll<HTMLElement>('[data-parallax]').forEach((el) => {
        const speed = parseFloat(el.dataset.parallax ?? '0.3');
        gsap.to(el, {
            yPercent: -30 * speed, ease: 'none',
            scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true },
        });
    });

    gsap.utils.toArray<HTMLElement>('.section-reveal').forEach((el) => {
        gsap.from(el, {
            y: 50, opacity: 0, duration: 0.8, ease: 'expo.out',
            scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        });
    });
}

// ═══ BOOT ═══
document.addEventListener('DOMContentLoaded', async () => {
    initLoadingScreen();
    Alpine.start();
    initLenis();
    initAOS();
    initCustomCursor();
    initScrollToTop();
    initSwipers();
    initScrollAnimations();
    initHeroCanvas().catch(console.error);
});

document.addEventListener('click', (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    const anchor = target.closest('a');
    if (!anchor) return;

    const href = anchor.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto') || href.startsWith('tel') || anchor.hasAttribute('target')) return;
    if (e.ctrlKey || e.metaKey || e.shiftKey) return;

    e.preventDefault();
    gsap.to('main', {
        opacity: 0, y: -20, duration: 0.3, ease: 'power2.in',
        onComplete: () => { window.location.href = href; },
    });
});

window.addEventListener('pageshow', () => {
    gsap.from('main', { opacity: 0, y: 20, duration: 0.5, ease: 'expo.out', clearProps: 'all' });
});
