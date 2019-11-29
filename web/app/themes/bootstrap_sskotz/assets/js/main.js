sidenavOpen = false;

function openSidenav() {
    $(".sidenav").removeClass('closed');
    $(".sidenav").addClass('opened');
    $(".sidenav").css('width', '250px');
    $('.canvas').css('margin-left', '250px');
    $('#toggle-sidenav .icon-menu').removeClass('fa-bars');
    $('#toggle-sidenav .icon-menu').addClass('fa-times');
    sidenavOpen = true;
}

function closeSidenav() {
    $(".sidenav").addClass('closed');
    $(".sidenav").removeClass('opened');
    $(".sidenav").css('width', '0px');
    $('.canvas').css('margin-left', '0px');
    $('#toggle-sidenav .icon-menu').removeClass('fa-times');
    $('#toggle-sidenav .icon-menu').addClass('fa-bars');
    sidenavOpen = false;
}

$(document).ready(function () {
    $("#toggle-sidenav").click(function (e) {
        if (sidenavOpen) {
            closeSidenav();
        } else {
            openSidenav();
        }
    })
});