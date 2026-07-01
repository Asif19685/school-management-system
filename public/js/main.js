$(document).ready(function () {
    // Toggle the sidebar
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
});
