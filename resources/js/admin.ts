/**
 * JTS Admin Dashboard — Frontend Entry Point
 */

import Alpine from 'alpinejs';
import Persist from '@alpinejs/persist';
import Collapse from '@alpinejs/collapse';
import { gsap } from 'gsap';
import { Chart, registerables } from 'chart.js';
import type { ChartConfiguration } from 'chart.js';

Chart.register(...registerables);

declare global {
    interface Window {
        Alpine: typeof Alpine;
        Chart: typeof Chart;
        AdminCharts: Record<string, Chart>;
        initChart: (id: string, config: ChartConfiguration) => Chart;
        destroyChart: (id: string) => void;
    }
}

Alpine.plugin(Persist);
Alpine.plugin(Collapse);
window.Alpine = Alpine;

Chart.defaults.color = '#818181';
Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
Chart.defaults.plugins.legend.labels.padding = 20;
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.elements.line.borderWidth = 2;
Chart.defaults.elements.line.tension = 0.4;
Chart.defaults.elements.point.radius = 3;
Chart.defaults.elements.point.hoverRadius = 6;
Chart.defaults.elements.point.backgroundColor = '#ff6600';
Chart.defaults.elements.point.borderColor = '#ff6600';

window.AdminCharts = {};

window.initChart = (id: string, config: ChartConfiguration): Chart => {
    if (window.AdminCharts[id]) window.AdminCharts[id].destroy();
    const canvas = document.getElementById(id) as HTMLCanvasElement;
    if (!canvas) throw new Error(`Canvas #${id} not found`);
    const chart = new Chart(canvas, config);
    window.AdminCharts[id] = chart;
    return chart;
};

window.destroyChart = (id: string): void => {
    if (window.AdminCharts[id]) { window.AdminCharts[id].destroy(); delete window.AdminCharts[id]; }
};

function initAdminSidebar(): void {
    const sidebar = document.getElementById('admin-sidebar');
    const toggleBtn = document.getElementById('admin-sidebar-toggle');
    const overlay = document.getElementById('admin-sidebar-overlay');
    if (!sidebar || !toggleBtn) return;

    const open = () => { sidebar.classList.add('open'); overlay?.classList.remove('hidden'); document.body.style.overflow = 'hidden'; };
    const close = () => { sidebar.classList.remove('open'); overlay?.classList.add('hidden'); document.body.style.overflow = ''; };

    toggleBtn.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
    overlay?.addEventListener('click', close);
}

export function csrfToken(): string {
    return (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '';
}

export async function apiRequest<T>(url: string, options: RequestInit = {}): Promise<T> {
    const response = await fetch(url, {
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'X-Requested-With': 'XMLHttpRequest', ...options.headers },
        ...options,
    });
    if (!response.ok) {
        const err = await response.json().catch(() => ({ message: response.statusText }));
        throw new Error(err.message ?? 'Request gagal');
    }
    return response.json();
}

function initNotificationPoll(): void {
    const badge = document.getElementById('notif-badge');
    if (!badge) return;

    const update = async () => {
        try {
            const data = await apiRequest<{ unread: number }>('/admin/notifications/unread-count');
            badge.textContent = data.unread > 0 ? String(data.unread) : '';
            badge.style.display = data.unread > 0 ? 'flex' : 'none';
        } catch { /* silent */ }
    };
    update();
    setInterval(update, 60_000);
}

function initDeleteConfirm(): void {
    document.querySelectorAll<HTMLElement>('[data-confirm-delete]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const message = el.dataset.confirmDelete || 'Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            if (!window.confirm(message)) { e.preventDefault(); e.stopImmediatePropagation(); }
        });
    });
}

function initImagePreview(): void {
    document.querySelectorAll<HTMLInputElement>('input[type="file"][data-preview]').forEach((input) => {
        const previewId = input.dataset.preview!;
        const preview = document.getElementById(previewId) as HTMLImageElement | null;
        if (!preview) return;
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file || !file.type.startsWith('image/')) return;
            const url = URL.createObjectURL(file);
            preview.src = url; preview.style.display = 'block';
            preview.onload = () => URL.revokeObjectURL(url);
        });
    });
}

function initAdminAnimations(): void {
    gsap.from('.admin-stat-card', { y: 20, opacity: 0, duration: 0.5, ease: 'power2.out', stagger: 0.08, delay: 0.2 });
}

function initFlashMessages(): void {
    document.querySelectorAll<HTMLElement>('[data-auto-dismiss]').forEach((el) => {
        const delay = parseInt(el.dataset.autoDismiss ?? '5000');
        setTimeout(() => { gsap.to(el, { opacity: 0, y: -10, duration: 0.3, onComplete: () => el.remove() }); }, delay);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
    initAdminSidebar();
    initDeleteConfirm();
    initImagePreview();
    initAdminAnimations();
    initFlashMessages();
    initNotificationPoll();
});

window.Chart = Chart;
