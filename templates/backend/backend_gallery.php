<div class="cards">
<?php 
echo "FILE [", __FILE__, "] FUNC [", __FUNCTION__, "] LINE [", __LINE__, "]<br /><textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
//print_r();
echo "</textarea><br />";
?>
    <div class="card">
        <div class="card__image-holder">
            <img class="card__image" src="https://source.unsplash.com/300x225/?wave" alt="wave" />
        </div>
        <div class="card-title">
            <a href="#" class="toggle-info btn">
                <span class="left"></span>
                <span class="right"></span>
            </a>
            <h2>
                Card title
                <small>Image from unsplash.com</small>
            </h2>
        </div>
        <div class="card-flap flap1">
            <div class="card-description">
                This grid is an attempt to make something nice that works on touch devices. Ignoring hover states when they're not available etc.
            </div>
            <div class="card-flap flap2">
                <div class="card-actions">
                    <a href="#" class="btn">Read more</a>
                </div>
            </div>
        </div>
    </div>
</div>



