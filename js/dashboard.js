$(document).ready(function() {
    // Toggle the sidebar visibility
    function toggleSidebar() {
      $("#sidebar").toggle();
    }
  
    // Bind the toggleSidebar function to the button click event
    $("#sidebarToggle").click(function() {
      toggleSidebar();
    });
  
    // Check the media query condition on window resize
    $(window).on("resize", function() {
      if (window.matchMedia("(max-width: 390px)").matches) {
        toggleSidebar();
      }
    });
  });