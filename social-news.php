
<style>

.ucf-social-links .btn-facebook.color {
    background-color: #3a5795
}

.ucf-social-links .btn-facebook.color:hover {
    background-color: #2c4270
}

.ucf-social-links .btn-linkedin.color {
    background-color: #0077b5
}

.ucf-social-links .btn-linkedin.color:hover {
    background-color: #005582
}

.ucf-social-links .btn-twitter.color {
    background-color: #00a5e5
}

.ucf-social-links .btn-twitter.color:hover {
    background-color: #0080b2
}

.ucf-social-links {
    content: '';
    display: table;
    line-height: 0;
		margin-top: 10px;
}

.ucf-social-links a, .ucf-social-links a:hover{
	color: white;
}

.ucf-social-links::after,.ucf-social-links::before {
    clear: both
}

.ucf-social-links .btn {
    background-image: none;
    border: 0;
    border-radius: 0;
    display: block;
    float: left;
    font-weight: 700;
    letter-spacing: .1em;
    margin-bottom: 5px;
    margin-right: 5px;
    text-shadow: none;
    text-transform: uppercase;
    -webkit-transition: none;
    transition: none;
}

.ucf-social-links .btn-sm {

    padding: 2px 8px
}

.ucf-social-links .btn-md {
    font-size: 12px;
    padding: 4px 10px
}

.ucf-social-links .btn-lg {
    font-size: 14px;
    padding: 6px 18px
}

@media (min-width: 900px){
    .ucf-social-links-affixed {
        display:-webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        left: 0;
        margin: 0;
        padding: 0;
        position: fixed;
        top: calc(10vh + 200px);
				margin-top: 0px;
    }

    .ucf-social-links-affixed .btn-sm {
        padding: 8px 14px;
    }

    .ucf-social-links-affixed .fa {
        font-size: 22px;
        vertical-align: middle
    }

    .ucf-social-links-affixed .btn-text {
        border: 0;
        clip: rect(0,0,0,0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }
}
</style>
<div class="ucf-social-links ucf-social-links-affixed">

		<a class="btn btn-facebook color btn-sm" target="_blank" href="<?php echo 'https://www.facebook.com/sharer.php?u=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" title="Like this content on Facebook">
		<span class="fa fa-facebook" aria-hidden="true"></span><span class="btn-text"> Like</span>
		</a>

		<a class="btn btn-twitter color btn-sm" target="_blank" href="<?php echo 'https://twitter.com/intent/tweet?text=' . get_the_title() . ';url=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" title="Tweet this content">
		<span class="fa fa-twitter" aria-hidden="true"></span><span class="btn-text"> Tweet</span>
		</a>

		<a class="btn btn-linkedin color btn-sm" target="_blank" href="<?php echo 'https://www.linkedin.com/shareArticle?mini=true&url=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&title=' . get_the_title() ?>" title="Share this content on LinkedIn">
		<span class="fa fa-linkedin-square" aria-hidden="true"></span><span class="btn-text"> Share</span>
		</a>

</div>
