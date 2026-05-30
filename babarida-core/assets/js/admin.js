/**
 * Babarida Core - Admin JavaScript
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const BabaridaAdmin = {
        
        init() {
            this.initDismissNotices();
            this.initAjaxForms();
            this.initTooltips();
        },

        // Dismiss custom notices
        initDismissNotices() {
            document.querySelectorAll('.babarida-notice.is-dismissible').forEach(notice => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'notice-dismiss';
                btn.innerHTML = '<span class="screen-reader-text">Dismiss this notice.</span>';
                btn.addEventListener('click', () => {
                    notice.style.opacity = '0';
                    notice.style.transform = 'translateY(-10px)';
                    setTimeout(() => notice.remove(), 300);
                });
                notice.appendChild(btn);
            });
        },

        // Handle AJAX form submissions safely
        initAjaxForms() {
            document.querySelectorAll('[data-babarida-ajax]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    
                    const action = form.dataset.babaridaAjax;
                    const btn = form.querySelector('button[type="submit"]');
                    const originalText = btn ? btn.innerHTML : '';
                    
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner is-active" style="margin:0 8px 0 0;"></span> Processing...';
                    }

                    const formData = new FormData(form);
                    formData.append('action', action);
                    formData.append('nonce', window.babaridaAdmin ? window.babaridaAdmin.nonce : '');

                    fetch(window.babaridaAdmin.ajaxUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.success) {
                            BabaridaAdmin.showNotice('success', response.data.message || 'Action completed successfully.');
                            if (response.data.reload) {
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        } else {
                            BabaridaAdmin.showNotice('error', response.data.message || 'An error occurred.');
                        }
                    })
                    .catch(() => {
                        BabaridaAdmin.showNotice('error', 'Network error. Please try again.');
                    })
                    .finally(() => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    });
                });
            });
        },

        // Simple tooltip system
        initTooltips() {
            document.querySelectorAll('[data-tooltip]').forEach(el => {
                el.style.position = 'relative';
                el.addEventListener('mouseenter', () => {
                    let tip = el.querySelector('.babarida-tooltip');
                    if (!tip) {
                        tip = document.createElement('div');
                        tip.className = 'babarida-tooltip';
                        tip.textContent = el.dataset.tooltip;
                        tip.style.cssText = `
                            position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%);
                            background: #0F172A; color: #fff; font-size: 12px; padding: 6px 12px;
                            border-radius: 4px; white-space: nowrap; z-index: 9999; margin-bottom: 8px;
                        `;
                        el.appendChild(tip);
                    }
                    tip.style.display = 'block';
                });
                el.addEventListener('mouseleave', () => {
                    const tip = el.querySelector('.babarida-tooltip');
                    if (tip) tip.style.display = 'none';
                });
            });
        },

        // Show floating notice
        showNotice(type, message) {
            const wrapper = document.querySelector('.babarida-admin-wrap') || document.querySelector('.wrap');
            if (!wrapper) return;

            const notice = document.createElement('div');
            notice.className = `babarida-notice babarida-notice-${type}`;
            notice.style.transition = 'all 0.3s ease';
            notice.innerHTML = `<span>${message}</span>`;
            
            wrapper.prepend(notice);
            
            setTimeout(() => {
                notice.style.opacity = '0';
                setTimeout(() => notice.remove(), 300);
            }, 5000);
        }
    };

    BabaridaAdmin.init();
});
