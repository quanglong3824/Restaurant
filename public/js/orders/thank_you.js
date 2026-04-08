/**
 * Thank You Page JavaScript - Aurora Restaurant
 * Handles star rating functionality
 */

/**
 * Handle star rating
 * @param {number} stars - Number of stars (1-5)
 */
function rate(stars) {
    const starEls = document.querySelectorAll('.star-rating i');
    starEls.forEach((el, index) => {
        if (index < stars) {
            el.classList.add('rated');
            el.style.color = '#f59e0b';
            el.style.transform = 'scale(1.1)';
        } else {
            el.classList.remove('rated');
            el.style.color = '#cbd5e1';
            el.style.transform = 'scale(1)';
        }
    });
    
    const msgEl = document.getElementById('ratingMessage');
    const msgs = [
        'Rất tệ',
        'Tệ',
        'Bình thường',
        'Hài lòng',
        'Tuyệt vời! Xin cảm ơn!'
    ];
    
    msgEl.textContent = msgs[stars - 1];
    msgEl.classList.add('visible');
    
    // Optional: Send rating to server
    // sendRatingToServer(stars);
}

/**
 * Send rating to server (optional implementation)
 * @param {number} rating - Rating value (1-5)
 */
function sendRatingToServer(rating) {
    // Implement API call to save rating
    // fetch('/api/rating', { method: 'POST', body: JSON.stringify({ rating }) });
    console.log('Rating submitted:', rating);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Clear session storage for menu cart
    sessionStorage.removeItem('aurora_cart');
    
    // Add keyboard support for star rating
    const starEls = document.querySelectorAll('.star-rating i');
    starEls.forEach((el, index) => {
        el.setAttribute('tabindex', '0');
        el.setAttribute('role', 'button');
        el.setAttribute('aria-label', 'Rate ' + (index + 1) + ' stars');
        
        el.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                rate(index + 1);
            }
        });
    });
});