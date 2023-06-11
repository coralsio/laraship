@component('components.box',['box_title'=>'Recently Viewed URLs'])
    <div id="recently_viewed_urls">

    </div>
@endcomponent

@section('js')
    @parent
    <script>
        function buildLinkForRecentlyViewedURLs(UrlDetails) {
            let link = $("<a>", {href: UrlDetails.link, title: UrlDetails.title}).text(UrlDetails.title);

            $.each(UrlDetails.data, function (key, value) {
                link.attr("data-" + key, value);
            });

            return link;
        }

        function displayRecentlyViewedURLs() {
            let viewedURLsList = getCookie('recently_viewed_urls_user_{{optional(user())->hashed_id}}', true);

            if (_.isEmpty(viewedURLsList)) {
                $('#recently_viewed_urls').html('<div class="alert alert-warning">No URLs yet!</div>');
                return;
            }

            viewedURLsList.forEach(function (urlDetails, index) {
                let link = buildLinkForRecentlyViewedURLs(urlDetails);
                let div = $("<div>", {class: 'recently_viewed_url_item'});
                let dateIcon = $("<i>", {class: "fa fa-clock-o mr-4 m-r-10", title: urlDetails.date})
                div.append(dateIcon);
                div.append(link);
                $('#recently_viewed_urls').append(div);
            });
        }

        displayRecentlyViewedURLs();

    </script>
@endsection
