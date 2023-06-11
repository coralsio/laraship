(function ($) {
    var $shadow_film = $('.shadow-film'),
        $mobile_menu_handler = $('.mobile-menu-handler'),
        $mobile_menu = $('#mobile-menu'),
        $side_list_handler = $('.dropdown.interactive'),
        $mobile_account_options_handler = $('.mobile-account-options-handler'),
        $account_options_menu = $('#account-options-menu'),
        $db_sidemenu_handler = $('.db-side-menu-handler'),
        $dashboard_options_menu = $('#dashboard-options-menu');

    $mobile_menu_handler.on('click', {id: '#mobile-menu'}, showSideMenu);
    $mobile_menu.children('.svg-plus').on('click', {id: '#mobile-menu'}, showSideMenu);

    $mobile_account_options_handler.on('click', {id: '#account-options-menu'}, showSideMenu);
    $account_options_menu.children('.svg-plus').on('click', {id: '#account-options-menu'}, showSideMenu);

    $db_sidemenu_handler.on('click', {id: '#dashboard-options-menu'}, showSideMenu);
    $dashboard_options_menu.children('.svg-plus').on('click', {id: '#dashboard-options-menu'}, showSideMenu);

    function showSideMenu(e) {
        var $menu = $(e.data.id);

        toggleVisibility($menu);
        toggleVisibility($shadow_film);
    }

    function toggleVisibility(togglableItem) {
        if (togglableItem.hasClass('closed')) {
            togglableItem
                .removeClass('closed')
                .addClass('open');
        } else {
            togglableItem
                .removeClass('open')
                .addClass('closed');
        }
    }

    $('#dashboard-options-menu .dropdown-item.interactive').on("click", function (e) {
        toggleInnerMenu($(this), e);
    });

    $('#dashboard-options-menu .inner-dropdown-item').on("click", function (e) {
        e.stopPropagation();
        return true;
    });



    function toggleInnerMenu($menu , e) {
        e.stopPropagation();
        console.log($menu);

        $menu
            .toggleClass('active')
            .children('.inner-dropdown')
            .slideToggle(600);
    }
})(jQuery);