
<div class="myModul">
    <p><?php echo $heading_title; ?></p>
    <?php foreach ($content as $item) { ?>
        <p>
            <?php foreach ($item as $category) { ?>
                <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a> >
            <?php } ?>
        </p>
    <?php } ?>
</div>
