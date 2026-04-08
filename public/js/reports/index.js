/**
 * Reports Index JavaScript - Aurora Restaurant
 * Handles progress bar animations and report interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle progress bars
    const progressBars = document.querySelectorAll('.progress-bar-fill[data-width]');
    progressBars.forEach(function(bar) {
        const width = bar.getAttribute('data-width');
        bar.style.width = width + '%';
    });
});