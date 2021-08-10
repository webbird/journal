<?php
    $add_article_button = '<form name="addarticle" action="'.$this->e('edit_url').'" method="post" enctype="multipart/form-data">'.
                          '<input type="submit" class="btn btn-primary" name="mod_journal_add_article" value="' . $this->t('Add article') . '" />'.
                          '</form>';
?>
        <div id="main-tab-content1" class="main-tab-content">
<?php if(count($data['articles'])>0): echo $add_article_button; ?>
            <div style="text-align:right;font-style:italic">
                <?php echo $this->t('Order by'), ": <span class=\"\" title=\"", ( $this->s('view_order')=='0' ? $this->t('The setting &quot;custom&quot; allows the manual sorting of articles via up/down arrows.') : '') ,"\">", $this->t($data['orders'][$this->s('view_order')]['order_name']) ?></span>
            </div>
<?php endif; ?>
            <form name="modify_<?= $this->e('sectionID'); ?>" action="<?= $this->e($data['edit_url']) ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="section_id" value="<?= $this->e('sectionID'); ?>" />
                <input type="hidden" name="page_id" value="<?= $this->e('pageID'); ?>" />
<?php if(count($data['articles'])>0): ?>
                <section>
                    <ol>
                        <!-- The first list item is the header of the table -->
                        <li class="item-container header">
                            <div>#</div>
                            <div class="attribute-container title-group">
                                <div><?= $this->t('Title') ?></div>
                                <div><?= $this->t('Group') ?></div>
                            </div>
                            <div><?= $this->t('Active') ?></div>
                            <div class="attribute-container images-tags">
                                <div><?= $this->t('Images') ?></div>
                                <div><?= $this->t('Tags') ?></div>
                            </div>
                            <div class="attribute-container start-end">
                                <div><?= $this->t('Starting date') ?></div>
                                <div><?= $this->t('Expiry date') ?></div>
                            </div>
                        </li>
<?php foreach($data['articles'] as $article): ?>
                        <li class="item-container">
                            <div>#</div>
                            <div class="attribute-container title-group">
                                <div><a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>">
                                        <span title="Article ID <?= $article->article_id ?>"><?= $article->title; ?></span>
                                    </a></div>
                                <div><a href="<?= $this->e($data['edit_url']) ?>section_id=<?= $this->e($data['sectionID']); ?>&amp;group_id=<?= $article->group_id; ?>&amp;tab=g" title="<?= $this->t('Modify') ?>">
                                        <?= $article->group_title ?>
                                    </a></div>
                            </div>
                            <div><a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>&amp;active=<?= $article->active!=0 ? '0':'1'; ?>');" title="<?php if ($article->active == 1): echo $this->t('Deactivate article'); else: echo $this->t('Activate article'); endif;?>">
<?php if ($article->active == 1): ?>
                                    <span class="fa fa-fw fa-check"></span>
<?php else: ?>
                                    <span class="fa fa-fw fa-eye-slash"></span>
<?php endif; ?></a></div>
                            <div class="attribute-container images-tags">
                                <div><?= (isset($article->images) && is_array($article->images) ? count($article->images) : '0') ?></div>
                                <div><?= (isset($article->tags)   && is_array($article->tags)   ? count($article->tags)   : '0') ?></div>
                            </div>
                            <div class="attribute-container start-end">
                                <div><?= $article->published_when>0 ? $this->bridge()->formatDate($article->published_when, true).' '.$this->t("o'clock") : ''; ?></div>
                                <div><?= $article->published_until>0 ? $this->bridge()->formatDate($article->published_until, true).' '.$this->t("o'clock") : '' ?></div>
                            </div>
                        </li>
<?php endforeach; ?>
                    </ol>
               </section>
            </form>
<?php endif; ?>
        </div>