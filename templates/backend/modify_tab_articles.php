<?php
    $add_article_button = '<form name="addarticle" action="'.$this->e($data['edit_url']).'" method="post">'
                        . '<input type="hidden" name="jac" value="add_article" />'
                        . '<input type="submit" class="btn btn-primary" name="mod_journal_add_article_btn" value="' . $this->t('Add article') . '" />'
                        . '</form>';
?>
        <div id="main-tab-content1" class="main-tab-content">
<?php if(isset($data['articles']) && count($data['articles'])>0): echo $add_article_button; ?>
            <div style="text-align:right;font-style:italic">
                <?= $this->t('Order by'); ?>:
                <span class="" title="<?= $this->s('view_order')=='6' ? $this->t('The setting &quot;custom&quot; allows the manual sorting of articles via up/down arrows.') : '' ?>">
                    <?= $this->t($data['orders'][$this->s('view_order')]['order_name']) ?>
                </span>
            </div>
            <form name="modify_<?= $this->e('sectionID'); ?>" action="<?= $this->e($data['edit_url']) ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="section_id" value="<?= $this->e('sectionID'); ?>" />
                <input type="hidden" name="page_id" value="<?= $this->e('pageID'); ?>" />
                <section>
                    <ol<?php if ($this->s('view_order') == 6): ?> id="sortable-items"<?php endif; ?>>
                        <!-- The first list item is the header of the table -->
                        <li class="item-container header">
                            <div>&nbsp;</div>
                            <div class="attribute-container title-group">
                                <div><?= $this->t('Title') ?></div>
                                <div><?= $this->t('Group') ?></div>
                            </div>
                            <div><?= $this->t('Active') ?></div>
                            <div class="attribute-container images-tags">
                                <div><?= $this->t('Images') ?></div>
                                <div><?= $this->t('Tags') ?></div>
                            </div>
                            <div><?= $this->t('Publishing dates') ?></div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                        </li>
<?php foreach($data['articles'] as $article): ?>
                        <li class="item-container" id="article__<?= $this->e($article->article_id) ?>">
                        <?php if ($this->s('view_order') == 6): ?>
                            <div class="draghandle"><span class="j-move"></span></div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                            <div class="attribute-container title-group">
                                <div>
                                    <a href="<?= $this->e($data['edit_url'].$data['delim']) ?>article_id=<?= $this->e($article->article_id); ?>">
                                        <span title="Article ID <?= $article->article_id ?>"><?= $article->title; ?></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="<?= $this->e($data['edit_url']) ?>section_id=<?= $this->e($data['sectionID']); ?>&amp;group_id=<?= $article->group_id; ?>&amp;tab=g" title="<?= $this->t('Modify') ?>">
                                        <?= $article->group_title ?>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['edit_url']) ?>article_id=<?= $article->article_id; ?>&amp;active=<?= $article->article_active=='Y'?1:0; ?>');" title="<?php if ($article->article_active == 'Y'): echo $this->t('Deactivate article'); else: echo $this->t('Activate article'); endif;?>">
                                    <span class="j-<?php if ($article->article_active == 'Y'): ?>checkmark<?php else: ?>cross<?php endif; ?>"></span>
                                </a> 
                            </div>
                            <div class="attribute-container images-tags">
                                <div><?= (isset($article->images) && is_array($article->images) ? count($article->images) : '0') ?></div>
                                <div><?= (isset($article->tags)   && is_array($article->tags)   ? count($article->tags)   : '0') ?></div>
                            </div>
                            <div><?= count($article->publishing_dates); ?></div>
                            <div>
                                <a href="<?= $this->e($data['edit_url']) ?>article_id=<?= $this->e($article->article_id); ?>">
                                    <span class="j-pencil"></span>
                                </a>
                                <a href="javascript: confirm_link('<?= $this->t('Are you sure?') ?>', '<?= $this->e($data['base_url']) ?>');" title="<?= $this->t('Delete') ?>">
                                    <span class="j-bin"></span>
                                </a>
                            </div>
                            <div>
                                <input type="checkbox" name="manage_articles[]" value="<?= $article->article_id ?>" onchange='javascript: document.getElementById("<?= $this->e($data['sectionID']) ?>_all").checked &= this.checked' />
                            </div>
                        <?php if ($this->s('view_order') == 6): ?>
                            <div class="draghandle"><span class="j-move"></span></div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                        </li>
<?php endforeach; ?>
                    </ol>
                    <div class="btnline">
                        <div class="mod_news_article_tools"><?php echo self::t('Marked') ?>:
                                <select name="action">
                                    <option value="" selected="selected"></option>
                                    <option value="copy"><?php echo self::t('Copy') ?></option>
                                    <option value="copy_with_tags"><?php echo self::t('Copy with tags') ?></option>
                                    <option value="move"><?php echo self::t('Move') ?></option>
                                    <option value="move_with_tags"><?php echo self::t('Move with tags') ?></option>
                                    <option value="delete"><?php echo self::t('Delete') ?></option>
                                    <option value="activate"><?php echo self::t('Activate') ?></option>
                                    <option value="deactivate"><?php echo self::t('Deactivate') ?></option>
                                    <option value="tags"><?php echo self::t('Assign tags') ?></option>
                                    <option value="group"><?php echo self::t('Assign group') ?></option>
                                </select>
                                <input class="btn btn-primary" name="continue" type="submit" onclick="return checkActionAndArticles()" value="<?php echo self::t('Continue') ?>" />
                             </div>
                    </div>
               </section>
            </form>
<?php endif; if(isset($data['form'])): ?>
            <h2><?= $this->t('Write article') ?></h2>
<?= $data['form']; ?>
            <?php include __DIR__.'/backend_gallery.php'; ?>
<?php endif; ?>
        </div>