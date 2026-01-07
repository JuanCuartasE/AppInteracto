/**
 * main.js - AppInteracto Global Scripts
 */
$(document).ready(function () {
    console.log('AppInteracto Initialized');

    // Sidebar Toggle for Mobile
    $('#sidebarToggle, .sidebar-overlay').on('click', function () {
        $('body').toggleClass('sidebar-open');
    });
});
