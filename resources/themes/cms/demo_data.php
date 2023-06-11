<?php
$categories = [];
$posts = [];

if (\Schema::hasTable('posts')
    && class_exists(\Corals\Modules\CMS\Models\Page::class)
    && class_exists(\Corals\Modules\CMS\Models\Post::class)
) {
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'home', 'type' => 'page',],
        array(
            'title' => 'Home',
            'slug' => 'home',
            'meta_keywords' => 'home',
            'meta_description' => 'home',
            'content' => ' <div id="slider">@slider(home-page-slider)</div><section id="feature">
<div class="container">
<div class="center wow fadeInDown">
<h2>Features</h2>
<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
</div>

<div class="row">
<div class="features">
<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-laptop"></i>
<h2>Fresh and Clean</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->

<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-comments"></i>
<h2>Retina ready</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->

<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-cloud-download"></i>
<h2>Easy to customize</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->

<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-leaf"></i>
<h2>Adipisicing elit</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->

<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-cogs"></i>
<h2>Sed do eiusmod</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->

<div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="feature-wrap">
<i class="fa fa-heart"></i>
<h2>Labore et dolore</h2>
<h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
</div>
</div><!--/.col-md-4-->
</div><!--/.services-->
</div><!--/.row-->
</div><!--/.container-->
</section><!--/#feature-->

<section id="recent-works">
<div class="container">
<div class="center wow fadeInDown">
<h2>Recent Works</h2>
<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
</div>

<div class="row">
<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item1.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme</a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item1.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item2.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme</a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item2.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item3.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme </a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item3.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item4.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">MultiPurpose theme </a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item4.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item5.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme</a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item5.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item6.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme </a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item6.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item7.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme </a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item7.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>

<div class="col-xs-12 col-sm-4 col-md-3">
<div class="recent-work-wrap">
<img class="img-responsive" src="/assets/themes/cms/images/portfolio/recent/item8.png"
alt="">
<div class="overlay">
<div class="recent-work-inner">
<h3><a href="#">Business theme </a></h3>
<p>There are many variations of passages of Lorem Ipsum available, but the majority</p>
<a class="preview" href="/assets/themes/cms/images/portfolio/full/item8.png"
rel="prettyPhoto"><i
class="fa fa-eye"></i> View</a>
</div>
</div>
</div>
</div>
</div><!--/.row-->
</div><!--/.container-->
</section><!--/#recent-works-->

<section id="middle">
<div class="container">
<div class="row">
<div class="col-sm-6 wow fadeInDown">
<div class="skill">
<h2>Our Skills</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
labore et dolore magna aliqua.</p>

<div class="progress-wrap">
<h3>Graphic Design</h3>
<div class="progress">
<div class="progress-bar  color1" role="progressbar" aria-valuenow="40"
aria-valuemin="0" aria-valuemax="100" style="width: 85%">
<span class="bar-width">85%</span>
</div>

</div>
</div>

<div class="progress-wrap">
<h3>HTML</h3>
<div class="progress">
<div class="progress-bar color2" role="progressbar" aria-valuenow="20" aria-valuemin="0"
aria-valuemax="100" style="width: 95%">
<span class="bar-width">95%</span>
</div>
</div>
</div>

<div class="progress-wrap">
<h3>CSS</h3>
<div class="progress">
<div class="progress-bar color3" role="progressbar" aria-valuenow="60" aria-valuemin="0"
aria-valuemax="100" style="width: 80%">
<span class="bar-width">80%</span>
</div>
</div>
</div>

<div class="progress-wrap">
<h3>Wordpress</h3>
<div class="progress">
<div class="progress-bar color4" role="progressbar" aria-valuenow="80" aria-valuemin="0"
aria-valuemax="100" style="width: 90%">
<span class="bar-width">90%</span>
</div>
</div>
</div>
</div>

</div><!--/.col-sm-6-->

<div class="col-sm-6 wow fadeInDown">
<div class="accordion">
<h2>Why People like us?</h2>
<div class="panel-group" id="accordion1">
<div class="panel panel-default">
<div class="panel-heading active">
<h3 class="panel-title">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1"
href="#collapseOne1">
Lorem ipsum dolor sit amet
<i class="fa fa-angle-right pull-right"></i>
</a>
</h3>
</div>

<div id="collapseOne1" class="panel-collapse collapse in">
<div class="panel-body">
<div class="media accordion-inner">
<div class="pull-left">
<img class="img-responsive"
src="/assets/themes/cms/images/accordion1.png">
</div>
<div class="media-body">
<h4>Adipisicing elit</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
eiusmod tempor incididunt ut labore</p>
</div>
</div>
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1"
href="#collapseTwo1">
Lorem ipsum dolor sit amet
<i class="fa fa-angle-right pull-right"></i>
</a>
</h3>
</div>
<div id="collapseTwo1" class="panel-collapse collapse">
<div class="panel-body">
Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1"
href="#collapseThree1">
Lorem ipsum dolor sit amet
<i class="fa fa-angle-right pull-right"></i>
</a>
</h3>
</div>
<div id="collapseThree1" class="panel-collapse collapse">
<div class="panel-body">
Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1"
href="#collapseFour1">
Lorem ipsum dolor sit amet
<i class="fa fa-angle-right pull-right"></i>
</a>
</h3>
</div>
<div id="collapseFour1" class="panel-collapse collapse">
<div class="panel-body">
Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor.
</div>
</div>
</div>
</div><!--/#accordion1-->
</div>
</div>

</div><!--/.row-->
</div><!--/.container-->
</section><!--/#middle-->

<section id="bottom">
<div class="container wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Company</h3>
<ul>
<li><a href="#">About us</a></li>
<li><a href="#">We are hiring</a></li>
<li><a href="#">Meet the team</a></li>
<li><a href="#">Copyright</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Support</h3>
<ul>
<li><a href="#">Faq</a></li>
<li><a href="#">Blog</a></li>
<li><a href="#">Forum</a></li>
<li><a href="#">Documentation</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Developers</h3>
<ul>
<li><a href="#">Web Development</a></li>
<li><a href="#">SEO Marketing</a></li>
<li><a href="#">Theme</a></li>
<li><a href="#">Development</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Our Partners</h3>
<ul>
<li><a href="#">Adipisicing Elit</a></li>
<li><a href="#">Eiusmod</a></li>
<li><a href="#">Tempor</a></li>
<li><a href="#">Veniam</a></li>
</ul>
</div>
</div><!--/.col-md-3-->
</div>
</div>
</section><!--/#bottom-->
',
            'published' => 1,
            'published_at' => '2017-11-16 14:26:52',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 16:27:04',
            'updated_at' => '2017-11-16 16:27:07',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'about-us', 'type' => 'page'],
        array(
            'title' => 'About Us',
            'slug' => 'about-us',
            'meta_keywords' => 'about us',
            'meta_description' => 'about us',
            'content' => '    <section id="about-us">
<div class="container">
<div class="skill-wrap clearfix">
<div class="center wow fadeInDown">
<h2>About <span>CMS</span></h2>
<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
</div>

<div class="row">
<div class="col-sm-3">
<div class="sinlge-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
<div class="joomla-skill">
<p><em>85%</em></p>
<p>Joomla</p>
</div>
</div>
</div>

<div class="col-sm-3">
<div class="sinlge-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="html-skill">
<p><em>95%</em></p>
<p>HTML</p>
</div>
</div>
</div>

<div class="col-sm-3">
<div class="sinlge-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="900ms">
<div class="css-skill">
<p><em>80%</em></p>
<p>CSS</p>
</div>
</div>
</div>

<div class="col-sm-3">
<div class="sinlge-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="1200ms">
<div class="wp-skill">
<p><em>90%</em></p>
<p>Wordpress</p>
</div>
</div>
</div>
</div>
</div>

<!-- our-team -->
<div class="team">
<div class="center wow fadeInDown">
<h2>Team of <span>CMS.</span></h2>
<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>
</div>

<div class="row clearfix">
<div class="col-md-4 col-sm-6">
<div class="single-profile-top wow fadeInDown" data-wow-duration="1000ms"
data-wow-delay="300ms">
<div class="media">
<div class="pull-left">
<a href="#"><img class="media-object" src="/assets/themes/cms/images/man1.jpg" alt=""></a>
</div>
<div class="media-body">
<h4>Jhon Doe</h4>
<h5>Founder and CEO</h5>
<ul class="tag clearfix">
<li class="btn"><a href="#">Web</a></li>
<li class="btn"><a href="#">Ui</a></li>
<li class="btn"><a href="#">Ux</a></li>
<li class="btn"><a href="#">Photoshop</a></li>
</ul>

<ul class="social_icons">
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
</ul>
</div>
</div><!--/.media -->
<p>There are many variations of passages of Lorem Ipsum available, but the majority have
suffered alteration in some form, by injected humour, or randomised words which don\'t
look even slightly believable.</p>
</div>
</div><!--/.col-lg-4 -->


<div class="col-md-4 col-sm-6 col-md-offset-2">
<div class="single-profile-top wow fadeInDown" data-wow-duration="1000ms"
data-wow-delay="300ms">
<div class="media">
<div class="pull-left">
<a href="#"><img class="media-object" src="/assets/themes/cms/images/man2.jpg" alt=""></a>
</div>
<div class="media-body">
<h4>Jhon Doe</h4>
<h5>Founder and CEO</h5>
<ul class="tag clearfix">
<li class="btn"><a href="#">Web</a></li>
<li class="btn"><a href="#">Ui</a></li>
<li class="btn"><a href="#">Ux</a></li>
<li class="btn"><a href="#">Photoshop</a></li>
</ul>
<ul class="social_icons">
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
</ul>
</div>
</div><!--/.media -->
<p>There are many variations of passages of Lorem Ipsum available, but the majority have
suffered alteration in some form, by injected humour, or randomised words which don\'t
look even slightly believable.</p>
</div>
</div><!--/.col-lg-4 -->
</div> <!--/.row -->
<div class="row team-bar">
<div class="first-one-arrow hidden-xs">
<hr>
</div>
<div class="first-arrow hidden-xs">
<hr>
<i class="fa fa-angle-up"></i>
</div>
<div class="second-arrow hidden-xs">
<hr>
<i class="fa fa-angle-down"></i>
</div>
<div class="third-arrow hidden-xs">
<hr>
<i class="fa fa-angle-up"></i>
</div>
<div class="fourth-arrow hidden-xs">
<hr>
<i class="fa fa-angle-down"></i>
</div>
</div> <!--skill_border-->

<div class="row clearfix">
<div class="col-md-4 col-sm-6 col-md-offset-2">
<div class="single-profile-bottom wow fadeInUp" data-wow-duration="1000ms"
data-wow-delay="600ms">
<div class="media">
<div class="pull-left">
<a href="#"><img class="media-object" src="/assets/themes/cms/images/man3.jpg" alt=""></a>
</div>

<div class="media-body">
<h4>Jhon Doe</h4>
<h5>Founder and CEO</h5>
<ul class="tag clearfix">
<li class="btn"><a href="#">Web</a></li>
<li class="btn"><a href="#">Ui</a></li>
<li class="btn"><a href="#">Ux</a></li>
<li class="btn"><a href="#">Photoshop</a></li>
</ul>
<ul class="social_icons">
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
</ul>
</div>
</div><!--/.media -->
<p>There are many variations of passages of Lorem Ipsum available, but the majority have
suffered alteration in some form, by injected humour, or randomised words which don\'t
look even slightly believable.</p>
</div>
</div>
<div class="col-md-4 col-sm-6 col-md-offset-2">
<div class="single-profile-bottom wow fadeInUp" data-wow-duration="1000ms"
data-wow-delay="600ms">
<div class="media">
<div class="pull-left">
<a href="#"><img class="media-object" src="/assets/themes/cms/images/man4.jpg" alt=""></a>
</div>
<div class="media-body">
<h4>Jhon Doe</h4>
<h5>Founder and CEO</h5>
<ul class="tag clearfix">
<li class="btn"><a href="#">Web</a></li>
<li class="btn"><a href="#">Ui</a></li>
<li class="btn"><a href="#">Ux</a></li>
<li class="btn"><a href="#">Photoshop</a></li>
</ul>
<ul class="social_icons">
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
</ul>
</div>
</div><!--/.media -->
<p>There are many variations of passages of Lorem Ipsum available, but the majority have
suffered alteration in some form, by injected humour, or randomised words which don\'t
look even slightly believable.</p>
</div>
</div>
</div>    <!--/.row-->
</div><!--section-->
</div><!--/.container-->
</section><!--/about-us-->

<section id="bottom">
<div class="container wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Company</h3>
<ul>
<li><a href="#">About us</a></li>
<li><a href="#">We are hiring</a></li>
<li><a href="#">Meet the team</a></li>
<li><a href="#">Copyright</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Support</h3>
<ul>
<li><a href="#">Faq</a></li>
<li><a href="#">Blog</a></li>
<li><a href="#">Forum</a></li>
<li><a href="#">Documentation</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Developers</h3>
<ul>
<li><a href="#">Web Development</a></li>
<li><a href="#">SEO Marketing</a></li>
<li><a href="#">Theme</a></li>
<li><a href="#">Development</a></li>
</ul>
</div>
</div><!--/.col-md-3-->

<div class="col-md-3 col-sm-6">
<div class="widget">
<h3>Our Partners</h3>
<ul>
<li><a href="#">Adipisicing Elit</a></li>
<li><a href="#">Eiusmod</a></li>
<li><a href="#">Tempor</a></li>
<li><a href="#">Veniam</a></li>
</ul>
</div>
</div><!--/.col-md-3-->
</div>
</div>
</section><!--/#bottom-->
',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'blog', 'type' => 'page'],
        array(
            'title' => 'Blog',
            'slug' => 'blog',
            'meta_keywords' => 'Blog',
            'meta_description' => 'Blog',
            'content' => '<div class="text-center">
<h2>Blog</h2>

<p class="lead">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
</div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'pricing', 'type' => 'page'],
        array(
            'title' => 'Pricing',
            'meta_keywords' => 'Pricing',
            'meta_description' => 'Pricing',
            'content' => '<div class="text-center">
<h2>Pricing</h2>

<p class="lead">Easy and Powerful products and plans management.</p>
</div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'contact-us', 'type' => 'page'],
        array(
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'meta_keywords' => 'Contact Us',
            'meta_description' => 'Contact Us',
            'content' => '<div><h2 style="text-align: center;">Drop Your Message</h2><p class="lead" style="text-align: center;">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'contact',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));

    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'subscription-commerce-trends-for-2018', 'type' => 'post'],
        array(
            'title' => 'Subscription Commerce Trends for 2018',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Subscription commerce is ever evolving. A few years ago, who would have expected&nbsp;<a href="https://techcrunch.com/2017/10/10/porsche-launches-on-demand-subscription-for-its-sports-cars-and-suvs/" target="_blank">Porsche</a>&nbsp;to launch a subscription service? Or that monthly boxes of beauty samples or shaving supplies and&nbsp;<a href="https://www.pymnts.com/subscription-commerce/2017/how-over-the-top-services-came-out-on-top/" target="_blank">OTT services</a>&nbsp;would propel the subscription model to new heights? And how will these trends shape the subscription space going forward&mdash;and drive growth and innovation?</p>

<p>Regardless of your billing model, there&rsquo;s an opportunity for you to capitalize on many of the current trends in subscription commerce&mdash;trends that will help you to continue to compete and succeed in your industry.</p>

<h3><strong>What are these trends and how can you learn more?</strong></h3>

<p>These trends are outlined in our &ldquo;Top Ten Trends for 2018&rdquo; which we publish every year to help subscription businesses understand the drivers which may impact them in 2018 and beyond.</p>

<p>One trend, for example, is machine learning and data science which the payments industry is increasingly utilizing to deliver more powerful results for their customers.</p>

<p>Another trend which is driving new revenue is the adoption of a hybrid billing model&mdash; subscription businesses seamlessly sell one-time items and &lsquo;traditional&rsquo; businesses add a subscription component as a means to introduce a new revenue stream.</p>

<p>And while subscriber acquisition is not a new trend, there are some sophisticated ways to acquire new customers that subscription businesses are putting to work for increasingly positive effect.</p>

<p>Download this year&rsquo;s edition and see how these trends and insights can help your subscription business succeed in 2018.</p>

<p>&nbsp;</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:18:23',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-03 23:45:51',
            'updated_at' => '2017-12-04 13:18:23',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'using-machine-learning-to-optimize-subscription-billing', 'type' => 'post'],
        array(
            'title' => 'Using Machine Learning to Optimize Subscription Billing',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>As a data scientist at Recurly, my job is to use the vast amount of data that we have collected to build products that make subscription businesses more successful. One way to think about data science at Recurly is as an extended R&amp;D department for our customers. We use a variety of tools and techniques, attack problems big and small, but at the end of the day, our goal is to put all of Recurly&rsquo;s expertise to work in service of your business.</p>

<p>Managing a successful subscription business requires a wide range of decisions. What is the optimum structure for subscription plans and pricing? What are the most effective subscriber acquisition methods? What are the most efficient collection methods for delinquent subscribers? What strategies will reduce churn and increase revenue?</p>

<p>At Recurly, we&#39;re focused on building the most flexible subscription management platform, a platform that provides a competitive advantage for your business. We reduce the complexity of subscription billing so you can focus on winning new subscribers and delighting current subscribers.</p>

<p>Recently, we turned to data science to tackle a big problem for subscription businesses: involuntary churn.</p>

<h3><strong>The Problem: The Retry Schedule</strong></h3>

<p>One of the most important factors in subscription commerce is subscriber retention. Every billing event needs to occur flawlessly to avoid adversely impacting the subscriber relationship or worse yet, to lose that subscriber to churn.</p>

<p>Every time a subscription comes up for renewal, Recurly creates an invoice and initiates a transaction using the customer&rsquo;s stored billing information, typically a credit card. Sometimes, this transaction is declined by the payment processor or the customer&rsquo;s bank. When this happens, Recurly sends reminder emails to the customer, checks with the Account Updater service to see if the customer&#39;s card has been updated, and also attempts to collect payment at various intervals over a period of time defined by the subscription business. The timing of these collection attempts is called the &ldquo;retry schedule.&rdquo;</p>

<p>Our ability to correct and successfully retry these cards prevents lost revenue, positively impacts your bottom line, and increases your customer retention rate.</p>

<p>Other subscription providers typically offer a static, one-size-fits-all retry schedule, or leave the schedule up to the subscription business, without providing any guidance. In contrast, Recurly can use machine learning to craft a retry schedule that is tailored to each individual invoice based on our historical data with hundreds of millions of transactions. Our approach gives each invoice the best chance of success, without any manual work by our customers.</p>

<p>A key component of Recurly&rsquo;s values is to test, learn and iterate. How did we call on those values to build this critical component of the Recurly platform?</p>

<h3><strong>Applying Machine Learning</strong></h3>

<p>We decided to use statistical models that leverage Recurly&rsquo;s data on transactions (hundreds of millions of transactions built up over years from a wide variety of different businesses) to predict which transactions are likely to succeed. Then, we used these models to craft the ideal retry schedule for each individual invoice. The process of building the models is known as machine learning.</p>

<p>The term &quot;machine learning&quot; encompasses many different processes and methods, but at its heart is an effort to go past explicitly programmed logic and allow a computer to arrive at the best logic on its own.</p>

<p>While humans are optimized for learning certain tasks&mdash;like how children can speak a new language after simply listening for a few months&mdash;computers can also be trained to learn patterns. Aggregating hundreds of millions of transactions to look for the patterns that lead to transaction success is a classic machine learning problem.</p>

<p>A typical machine learning project involves gathering data, training a statistical model on that data, and then evaluating the performance of the model when presented with new data. A model is only as good as the data it&rsquo;s trained on, and here we had a huge advantage.</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:21:25',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-04 13:21:25',
            'updated_at' => '2017-12-04 13:21:25',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'why-you-need-a-blog-subscription-landing-page', 'type' => 'post'],
        array(
            'title' => 'Why You Need A Blog Subscription Landing Page',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Whether subscribing via email or RSS, your site visitor is individually volunteering to add your content to their day; a day that is already crowded with content from emails, texts, voicemails, site content, and even snail mail. &nbsp;</p>

<p>As a business, each time you receive a new blog subscriber, you have received validation or &quot;a vote&quot; that your audience has identified YOUR content as adding value to their day. With each new blog subscriber, your content is essentially being awarded as being highly relevant to conversations your readers are having on a regular basis.&nbsp;</p>

<p>To best promote the content your blog subscribers can expect to receive on an ongoing basis,&nbsp;<strong>consider adding a blog subscription landing page.&nbsp;</strong>This is a quick win that will help your company enhance the blogging subscription experience and help you measure and manage the success of this offer with analytical insight.</p>

<p>Holistically, your goal with this landing page is to provide visitors with a sneak preview of the experience they will receive by becoming a blog subscriber.<strong>&nbsp;Your blog subscription landing page should include:</strong></p>

<ul>
<li><strong>A high-level overview of topics, categories your blog will discuss.&nbsp;&nbsp;</strong>For example, HubSpot&#39;s blog covers &quot;all of the inbound marketing - SEO, Blogging, Social Media, Landing Pages, Lead Generation, and Analytics.&quot;</li>
<li><strong>Insight into &quot;who&quot; your blog will benefit.&nbsp;&nbsp;</strong>Examples may include HR Directors, Financial Business professionals, Animal Enthusiasts, etc.&nbsp; If your blog appeals to multiple personas, feel free to spell this out.&nbsp; This will help assure your visitor that they are joining a group of like-minded individuals who share their interests and goals.&nbsp;&nbsp;</li>
<li><strong>How your blog will help to drive the relevant conversation.&nbsp;</strong>Examples may include &quot;updates on industry events&quot;, &quot;expert editorials&quot;, &quot;insider tips&quot;, etc.&nbsp;&nbsp;</li>
</ul>

<p><strong>To create your blog subscription landing page, consider the following steps:</strong></p>

<p>1) Create your landing page following&nbsp;landing page best practices.&nbsp; Consider the &quot;subscribing to your blog&quot; offer as similar to other offers you promote using Landing Pages.&nbsp;</p>

<p>2) Create a Call To Action button that will link to this landing page.&nbsp; Use this button as a call to action within your blog articles or on other website pages to link to a blog subscription landing page&nbsp;Make sure your CTA button is supercharged!</p>

<p>3)&nbsp;Create a Thank You Page&nbsp;to complete the sign-up experience with gratitude and a follow-up call to action.&nbsp;</p>

<p>4) Measure the success of your blog subscription landing page.&nbsp;Consider the 3 Secrets to Optimizing Landing Page Copy.&nbsp;</p>

<p>For more information on Blogging Success Strategies,&nbsp;check out more Content Camp Resources and recorded webinars.&nbsp;</p>',
            'published' => 1,
            'published_at' => '2017-12-04 11:33:19',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-12-04 13:31:46',
            'updated_at' => '2017-12-04 13:33:19',
        ));

}

if (\Schema::hasTable('categories') && class_exists(\Corals\Modules\CMS\Models\Category::class)) {
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Computers',
        'slug' => 'computers',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Smartphone',
        'slug' => 'smartphone',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Gadgets',
        'slug' => 'gadgets',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Technology',
        'slug' => 'technology',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Engineer',
        'slug' => 'engineer',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Subscriptions',
        'slug' => 'subscriptions',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Billing',
        'slug' => 'billing',
    ]);
}

$posts_media = [
    0 => array(
        'id' => 4,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'subscription_trends',
        'file_name' => 'subscription_trends.png',
        'mime_type' => 'image/png',
        'disk' => 'media',
        'size' => 20486,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 6,
        'created_at' => '2017-12-03 23:45:51',
        'updated_at' => '2017-12-03 23:45:51',
    ),
    1 => array(
        'id' => 8,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'machine_learning',
        'file_name' => 'machine_learning.png',
        'mime_type' => 'image/png',
        'disk' => 'media',
        'size' => 32994,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 11,
        'created_at' => '2017-12-04 13:21:25',
        'updated_at' => '2017-12-04 13:21:25',
    ),
    2 => array(
        'id' => 9,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'Successful-Blog_Fotolia_102410353_Subscription_Monthly_M',
        'file_name' => 'Successful-Blog_Fotolia_102410353_Subscription_Monthly_M.jpg',
        'mime_type' => 'image/jpeg',
        'disk' => 'media',
        'size' => 182317,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 12,
        'created_at' => '2017-12-04 13:33:19',
        'updated_at' => '2017-12-04 13:33:19',
    ),
];

foreach ($posts as $index=>$post) {
    $randIndex = rand(0, 6);
    if (isset($categories[$randIndex])) {
        $category = $categories[$randIndex];
        try {
            \DB::table('category_post')->insert(array(
                array(
                    'post_id' => $post->id,
                    'category_id' => $category->id,
                )
            ));
        } catch (\Exception $exception) {
        }
    }

    if (isset($posts_media[$index])) {
        try {
            $posts_media[$index]['model_id'] = $post->id;
            \DB::table('media')->insert($posts_media[$index]);
        } catch (\Exception $exception) {
        }
    }
}

if (class_exists(\Corals\Menu\Models\Menu::class) && \Schema::hasTable('posts')) {
    // seed root menus
    $topMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_top'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Top',
        'description' => 'Frontend Top Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $topMenuId = $topMenu->id;

    // seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'home'], [
        'parent_id' => $topMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => 'fa fa-home',
        'target' => null,
        'order' => 0
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 970
    ]);

    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'blog',
        'active_menu_url' => 'blog',
        'name' => 'Blog',
        'description' => 'Blog Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'pricing',
        'active_menu_url' => 'pricing',
        'name' => 'Pricing',
        'description' => 'Pricing Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);

    $footerMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_footer'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Footer',
        'description' => 'Frontend Footer Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $footerMenuId = $footerMenu->id;

// seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'footer_home'], [
        'parent_id' => $footerMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => 'fa fa-home',
        'target' => null,
        'order' => 0
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
}
