<?php

use yii\helpers\Html;

/**
 * @var $scheme \common\models\Scheme
 */

$this->registerJsFile("@web/js/scheme.js");

//$this->title = 'ЛР №' . $scheme->lab->id . '. ' . $scheme->lab->name;
//$this->params['breadcrumbs'][] = ['label' => 'Лабораторные работы', 'url' => ['lab/index']];
//$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['lab/update', 'id' => $lab->id]];
//$this->params['breadcrumbs'][] = 'Изменение схем для лабораторной работы';
?>

<div class="container-fluid">

    <!--Добавление контура-->
    <div class="circuits-setting">
        <div class="form-group">
            <button type="button" class="btn btn-primary circuit-preview">Предосмотр</button>
            <button type="button" class="btn btn-primary circuit-add">Добавить</button>
        </div>
    </div>

    <!--Редактирование элементов-->
    <div class="elements-setting hidden">
        <div class="form-group">
            <label for="element-select">Выберите элемент</label>
            <select id="element-select" class="form-control form-control-lg">
                <option value="R">Резистор</option>
                <option value="C">Конденсатор</option>
                <option value="L">Катушка индуктивности</option>
            </select>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="element-name">Название</label>
                    <input type="text" id="element-name" class="form-control" placeholder="Название">
                </div>
                <div class="col">
                    <label for="element-value">Значение</label>
                    <input type="text" id="element-value" class="form-control" placeholder="Значение">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="x-coordinate">Координата по x</label>
                    <input type="text" id="x-coordinate" class="form-control" placeholder="x">
                </div>
                <div class="col">
                    <label for="y-coordinate">Координата по y</label>
                    <input type="text" id="y-coordinate" class="form-control" placeholder="y">
                </div>
                <div class="col">
                    <label for="vertical">Веритикально</label>
                    <input type="checkbox" id="vertical" class="form-control">
                </div>
                <div class="col">
                    <label for="direction">Влево</label>
                    <input type="checkbox" id="direction" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary element-add">Добавить</button>
        </div>
    </div>

    <div class="row">

        <div class="col">
            <canvas id="scheme" width="640" height="360"></canvas>
        </div>

        <!--для контуров-->
        <div class="col circuits-panel">
            <div class="panel panel-default">
                <div class="panel-heading">Контура</div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <ul class="list-group circuits-list">
                            <?php /** @var \common\models\SchemeCircuit $circuit */ ?>
                            <?php foreach ($scheme->schemeCircuits as $circuit): ?>
                                <li class="list-group-item circuits-list-item">
                                    <ul class="list-group circuit-items">
                                        <?php foreach ($circuit as $point): ?>
                                            <li class="list-group-item">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" id="circuit-x" class="form-control" placeholder="x" value="<?= $point['x'] ?>">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" id="circuit-y" class="form-control" placeholder="y" value="<?= $point['y'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="btn-sm btn-primary circuit-point-add">Добавить точку</button>
                                    <button type="button" class="btn-sm btn-danger circuit-remove">Удалить</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--для элементов-->
        <div class="col elements-panel hidden">
            <div class="panel panel-default">
                <div class="panel-heading">Элементы</div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <ul class="list-group elements-list">
                            <?php /** @var \common\models\SchemeItem $schemeItem */ ?>
                            <?php foreach ($scheme->schemeItems as $schemeItem): ?>
                                <li class="list-group-item"
                                    data-type="<?= $schemeItem->type ?>"
                                    data-name="<?= $schemeItem->name ?>"
                                    data-value="<?= $schemeItem->value ?>"
                                    data-x="<?= $schemeItem->x ?>"
                                    data-y="<?= $schemeItem->y ?>"
                                    data-vertical="<?= $schemeItem->vertical ? 'true' : 'false' ?>"
                                    data-direction="<?= $schemeItem->direction ? 'true' : 'false' ?>">
                                    <div class="row">
                                        <div class="col-10"><p><?= $schemeItem->name ?></p></div>
                                        <div class="col-2">
                                            <button type="button" data-id="<?= $schemeItem->id ?>"
                                                    class="btn btn-default btn-sm element-remove"><span
                                                        class="glyphicon glyphicon-remove"></span></button>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary save">Сохранить</button>
    </div>
</div>