<?php
/**
 * @var $this View
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
use yii\helpers\Html;
use yii\web\View;

?>
<?php echo Html::beginTag('div', $options); ?>

    <!-- Gallery Toolbar -->
    <div class="btn-toolbar" style="padding: 4px">
        <div class="btn-group" style="display: inline-block;">
            <div class="btn btn-success btn-file" style="display: inline-block">
                <i class="glyphicon glyphicon-plus"></i>
                添加
                <input type="file" name="image" class="afile" accept="image/*" multiple="multiple" />
            </div>
        </div>
        <div class="btn-group" style="display: inline-block;">
            <label class="btn btn-default">
                <input type="checkbox" style="margin-right: 4px;" class="select_all">
                全选
            </label>
            <div class="btn btn-default disabled remove_selected">
                <i class="glyphicon glyphicon-remove"></i>
                删除
            </div>
        </div>
    </div>

    <hr/>

    <!-- Gallery Photos -->
    <div class="sorter">
        <div class="images"></div>
        <br style="clear: both;" />
    </div>

    <div class="overlay">
        <div class="overlay-bg">&nbsp;</div>
        <div class="drop-hint">
            <span class="drop-hint-info">
                拖拽文件到这儿
            </span>
        </div>
    </div>
    <div class="progress-overlay">
        <div class="overlay-bg">&nbsp;</div>
        <!-- Upload Progress Modal-->
        <div class="modal progress-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>上传图片</h3>
                    </div>
                    <div class="modal-body">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-info progress-bar-striped active upload-progress"
                                 role="progressbar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo Html::endTag('div'); ?>
