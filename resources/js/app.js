// WallpaperHunt - Main JavaScript

// Like wallpaper function
window.likeWallpaper = async function(id, btn) {
    try {
        const csrf = window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const res = await fetch(`/wallpaper/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        if (res.status === 401) {
            const errorData = await res.json();
            window.showToast?.(errorData.message, 'error');
            setTimeout(() => window.location.href = errorData.redirect, 1500);
            return;
        }

        const data = await res.json();

        if (data.success) {
            const icon = btn.querySelector('i');
            if (icon) {
                if (data.liked) {
                    icon.className = 'ph-fill ph-heart text-red-500';
                    window.showToast?.('Đã lưu vào danh sách yêu thích!', 'success');
                } else {
                    icon.className = 'ph ph-heart';
                    window.showToast?.('Đã bỏ yêu thích.', 'info');
                }
            }

            // Update like counts
            const likeCount = document.getElementById('like-count');
            if (likeCount) {
                likeCount.textContent = Number(data.likes).toLocaleString();
            }

            const countEl = btn.querySelector('.like-count');
            if(countEl) countEl.innerText = data.likes;
        }
    } catch (e) {
        window.showToast?.('Có lỗi xảy ra', 'error');
    }
};

// Intersection Observer and Card Clicks
document.addEventListener('DOMContentLoaded', () => {
    // Fade-in animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.wallpaper-card, .category-card, .album-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity {0.5s} ease, transform 0.5s ease';
        observer.observe(el);
    });

    // Make entire wallpaper card clickable as fallback
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.wallpaper-card');
        if (!card) return;

        // Don't trigger if clicked on a button or link inside the card
        if (e.target.closest('button') || e.target.closest('a')) {
            return;
        }

        const id = card.dataset.wallpaperId;
        if (id) {
            window.location.href = `/wallpaper/${id}`;
        }
    });
});
