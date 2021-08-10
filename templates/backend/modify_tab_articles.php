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
                <div class="scrollable">
                    <table class="table table-sm table-striped dragdrop_form" id="mod_news_article_list">
                        <thead class="thead-dark">
                            <tr>
                                <th></th>
                                <th></th>
                                <th><?= $this->t('Title') ?></th>
                                <th><?= $this->t('Group') ?></th>
                                <th><?= $this->t('Active') ?></th>
                                <th><?= $this->t('Tags') ?></th>
                                <th><?= $this->t('Images') ?></th>
                                <th><?= $this->t('Starting date') ?></th>
                                <th><?= $this->t('Expiry date') ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><input type="checkbox" name="manage_articles_all" id="<?= $this->e('sectionID'); ?>_all" value="all" onchange='javascript: var boxes = document.forms["modify_<?= $this->e('sectionID'); ?>"].elements[ "manage_articles[]" ]; for (var i=0, len=boxes.length; i<len; i++) { boxes[i].checked = this.checked;}' /></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach($data['articles'] as $article): ?>
                            <tr id="article_id:<?= $article->article_id; ?>">
                                <td<?php if ($this->s('view_order') == 0): echo ' class="dragdrop_item"'; endif; ?>><i class="fa fas fa-fw fa-arrows-alt-v"></i></td>
                                <td>
                                    <a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>" title="<?= $this->t('Modify') ?>">
                                        <span class="fa fa-fw fa-edit"></span>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>">
                                        <span title="Article ID <?= $article->article_id ?>"><?= $article->title; ?></span>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= $this->e($data['edit_url']) ?>section_id=<?= $this->e($data['sectionID']); ?>&amp;group_id=<?= $article->group_id; ?>&amp;tab=g" title="<?= $this->t('Modify') ?>">
                                        <?= $article->group_title ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>&amp;active=<?= $article->active!=0 ? '0':'1'; ?>');" title="<?php if ($article->active == 1): echo $this->t('Deactivate article'); else: echo $this->t('Activate article'); endif;?>">
<?php if ($article->active == 1): ?>
                                    <span class="fa fa-fw fa-check"></span>
<?php else: ?>
                                    <span class="fa fa-fw fa-eye-slash"></span>
<?php endif; ?></a>
                                </td>
                                <td>
                                    <?= $article->tags ?>
                                </td>
                                <td>
                                    <?= (isset($article->images) && is_array($article->images) ? count($article->images) : '0') ?>
                                </td>
                                <td>
                                    <?php echo ($article->published_when>0 ? \CAT\Addon\cmsbridge::formatDate($article->published_when) : '') ?>
                                </td>
                                <td>
                                    <?php echo ($article->published_until>0 ? \CAT\Addon\cmsbridge::formatDate($article->published_until) : '') ?>
                                </td>
                                <td>
<?php if ($article->is_visible == true): ?>
                                    <span class="fa fa-fw fa-calendar-check" title="<?= $this->t('Article is visible') ?>"></span>
<?php else: ?>
                                    <span class="fa fa-fw fa-calendar-times" title="<?= $this->t('Article is NOT visible') ?>"></span>
<?php endif; ?></a>

                                </td>
                                <td>
<?php if (($article->position != 1) && ($this->s('view_order') == 0)): ?>
                                    <a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>&amp;move=up" title="<?= $this->t('Move up') ?>">
                                        <span class="fa fa-fw fa-arrow-circle-up mod_journal_arrow"></span>
                                    </a>
<?php else: ?>
                                    <span class="fa fa-fw fa-arrow-circle-up nwi-disabled mod_journal_arrow"></span>
<?php endif; ?>
                                </td>
                                <td>
<?php  if (($article->position != $data['num_articles']) && ($this->s('view_order') == 0)): ?>
                                    <a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>&amp;move=down" title="<?= $this->t('Move down') ?>">
                                        <span class="fa fa-fw fa-arrow-circle-down mod_journal_arrow"></span>
                                    </a>
<?php else: ?>
                                    <span class="fa fa-fw fa-arrow-circle-down nwi-disabled mod_journal_arrow"></span>
<?php endif; ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="manage_articles[]" value="<?= $article->article_id ?>" onchange='javascript: document.getElementById("<?= $this->e($data['sectionID']) ?>_all").checked &= this.checked' />
                                </td>
                                <td>
                                    <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['base_url']); ?>/modules/journal/delete_article.php?page_id=<?= $this->e($data['pageID']); ?>&amp;section_id=<?= $this->e($data['sectionID']); ?>&amp;article_id=<?= $article->article_id; ?>');" title="<?= $this->t('Delete') ?>">
                                        <span class="fa fa-fw fa-trash nwi-delete"></span>
                                    </a>
                                </td>
                                <td<?php if ($this->s('view_order') == 0): ?> class="dragdrop_item"<?php endif; ?>>&nbsp;</td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
<?php endif; ?>
        </div>