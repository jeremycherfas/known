<?php

    /* @var \Idno\Common\Entity $vars ['object'] */

    $replies = $vars['object']->countAnnotations('reply');
    $likes = $vars['object']->countAnnotations('like');
    $has_liked = false;
if ($like_annotations = $vars['object']->getAnnotations('like')) {
    foreach ($like_annotations as $like) {
        if (\Idno\Core\Idno::site()->session()->isLoggedOn()) {
            if ($like['owner_url'] == \Idno\Core\Idno::site()->session()->currentUser()->getDisplayURL()) {
                $has_liked = true;
            }
        }
    }
}
    $owner = $vars['object']->getOwner();

if (!empty($owner)) {

    ?>
        <div class="permalink">
            <p>
                <a href="<?php echo $owner->getDisplayURL() ?>"><?php echo $owner->getTitle() ?></a><?= \Idno\Core\Idno::site()->language()->_('published this'); ?>
                <a class="u-url url" href="<?php echo $vars['object']->getDisplayURL() ?>" rel="permalink"><time class="dt-published"
                          datetime="<?php echo date('c', $vars['object']->created) ?>"><?php echo date('F j, Y', $vars['object']->created) ?></time></a>
            <?php

            if ($vars['object']->access != 'PUBLIC') {
                ?><i class="fa fa-lock"> </i><?php
            }

            ?>
                <?php echo $this->draw('content/edit') ?>
                <?php echo $this->draw('content/end/links') ?>
                <?php

                if (\Idno\Core\Idno::site()->currentPage()->isPermalink() && \Idno\Core\Idno::site()->config()->indieweb_citation) {

                    ?>
                        <span class="citation"><?php echo $vars['object']->getCitation() ?></span>
                    <?php

                }

                ?>
            </p>
        </div>
        <div class="interactions">         
             <span class="annotate-icon">
            <?php
            if (!$has_liked) {
                $heart = '<i class="fa fa-star-o"></i>';
            } else {
                $heart = '<i class="fa fa-star"></i>';
            }
            if ($likes == 1) {
                $heart .= ' 1 star';
            } else {
                $heart .= ' ' . $likes . ' stars';
            }
            if (\Idno\Core\Idno::site()->session()->isLoggedOn()) {
                echo \Idno\Core\Idno::site()->actions()->createLink(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'annotation/post', $heart, ['type' => 'like', 'object' => $vars['object']->getUUID()], ['method' => 'POST', 'class' => 'stars']);
            } else {
                ?>
                    <a class="stars" href="<?php echo $vars['object']->getDisplayURL() ?>#comments"><?php echo $heart ?></a></span>
                <?php
            }
            ?>
                    <span class="annotate-icon"><a class="comments" href="<?php echo $vars['object']->getDisplayURL() ?>#comments"><i class="fa fa-comments"></i> <?php

                    //echo $replies;
                    if ($replies == 1) {
                        echo '1 comment';
                    } else {
                        echo $replies . ' comments';
                    }

                    ?></a></span>
            <a class="shares"
               href="<?php echo $vars['object']->getDisplayURL() ?>#comments"><?php if ($shares = $vars['object']->countAnnotations('share')) {
                    echo '<i class="fa fa-retweet"></i> ' . $shares;
} ?></a>
            <a class="rsvps"
               href="<?php echo $vars['object']->getDisplayURL() ?>#comments"><?php if ($rsvps = $vars['object']->countAnnotations('rsvp')) {
                    echo '<i class="fa fa-calendar-o"></i> ' . $rsvps;
} ?></a>
        </div>
        <br class="clearall"/>
        <?php

        if (\Idno\Core\Idno::site()->currentPage()->isPermalink()) {

            if (!empty($likes) || !empty($replies) || !empty($shares) || !empty($rsvps)) {

                ?>

                <div class="annotations">

                    <a name="comments"></a>
                    <?php echo $this->draw('content/end/annotations') ?>
                </div>

                <?php

            }

            echo $this->draw('entity/annotations/comment/main');

            echo $this->draw('content/syndication/links');

        } else {

            ?>
            <div class="extra-metadata">
                <?php echo $this->draw('content/syndication/links')?>
            </div>
            <?php

            if (\Idno\Core\Idno::site()->session()->isLoggedOn()) {
                echo $this->draw('entity/annotations/comment/mini');
            }

        }
}

