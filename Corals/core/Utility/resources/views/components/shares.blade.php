<div class="entry-share my-2 text-center" style="display: inline-block;width: 100%;">
    <div class="share-links" style="display: inline;">
        <a class="social-button round border sb-facebook" href="https://www.facebook.com/sharer.php?u={{ $url }}"
           onClick="openInNewWindow(this.href,this.title);"
           data-toggle="tooltip" data-placement="top" title="Facebook">
            <i class="fa fa-facebook"></i>
        </a>
        <a class="social-button round border sb-twitter"
           href="https://twitter.com/share?url={{ $url }}&text={{ $title }}" data-toggle="tooltip"
           onClick="openInNewWindow(this.href,this.title);"
           data-placement="top" title="Twitter"><i class="fa fa-twitter"></i>
        </a>
        <a class="social-button round border sb-pinterest"
           href="https://pinterest.com/pin/create/button/?url={{ $url }}" data-toggle="tooltip"
           onClick="openInNewWindow(this.href,this.title);"
           data-placement="top" title="Pinterest"><i class="fa fa-pinterest"></i>
        </a>
        <a class="social-button round border sb-google-plus" href="https://plus.google.com/share?url={{{ $url }}}"
           data-toggle="tooltip"
           onClick="openInNewWindow(this.href,this.title);"
           data-placement="top" title="Google +"><i class="fa fa-google-plus"></i>
        </a>
    </div>
</div>
