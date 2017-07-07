<?php // $this->widget->movie->comment($movie)
$_comment = function ($row) {
    ob_start();
    //pr($row);
    $name = $row->user ? $row->user->name : 'Admin';
    $img = (isset($row->user->avatar) && $row->user->avatar) ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png');
    ?>
    <a class="pull-left" href="#">
        <img alt=""
             src="<?php echo $img ?>"
             class="avatar">
    </a>
    <span class="name"><b class="red"><?php echo $name . ' </b>- ' . $row->_created_time ?></span>
    <p class="comment-content"><?php echo $row->content ?></p>

    <?php
    return ob_get_clean();
}
?>
<ul class="list-unstyled comment-list">
    <?php
    foreach ($list as $row) {
        ?>
        <li>
            <?php echo $_comment($row) ?>
            <div class="comment-btn">
                <a data-toggle="collapse" href="#reply_<?php echo $row->id ?>" aria-expanded="false"
                   aria-controls="reply_<?php echo $row->id ?>"
                   class="reply-btn">Trả lời (<?php echo count($row->subs) ?>) </a>
            </div>
            <div class="collapse  mt20 ml30 " id="reply_<?php echo $row->id ?>">
                <form id="commentForm" class="form_action" accept-charset="UTF-8"
                      action="<?php echo site_url('comment/add') ?>" method="POST">
                    <input type="hidden" name="table_id" value="<?php echo $info->id ?>"/>
                    <input type="hidden" name="table_name" value="course"/>
                    <input type="hidden" name="parent_id" value="<?php echo $row->id ?>" >

                    <!--<img src="<?php /*//echo !$user->avatar?$user->avatar->url_thumb:public_url('site/layout/img/default-avatar.png')*/ ?>" class="media-object user-avatar pull-left">-->

                    <div class="form-group text-right">
                                            <textarea style="width: 70%;height: 60px;float:left;margin-right:20px"
                                                      name="content"
                                                      placeholder="<?php echo lang("comment") ?>..."
                                                      class="form-control"></textarea>
                        <input type="submit" value="Trả lời"
                               class="btn btn-primary btn-xs pull-left">

                        <div class="clear"></div>
                        <div name="content_error" class="error">
                        </div>
                    </div>
                </form>
                <?php if (isset($row->subs) && $row->subs): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($row->subs as $sub): //pr($sub);?>
                            <li>
                                <?php echo $_comment($sub) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </li>

        <?php
    }
    ?>
</ul>
