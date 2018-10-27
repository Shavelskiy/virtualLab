<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-8">
                <canvas id="oscilloscope" width="700" height="400"></canvas>
            </div>
            <div class="col-4 my-auto" id="settings">
                <?php for ($i = 1; $i <= 2; $i++): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id="<?= $i ?>">
                            <label for="active">Канал <?= $i ?></label>
                            <input type="checkbox" id="active">
                        </div>
                        <div class="panel-body" id="<?= $i ?>">
                            <label for="timeDiv">Время на деление:</label>
                            <select class="form-control" id="timeDiv">
                                <option value="0.05">50 us</option>
                                <option value="0.1">100 us</option>
                                <option value="0.2">200 us</option>
                                <option value="0.5">500 us</option>
                                <option value="1" selected>1 ms</option>
                                <option value="2">2 ms</option>
                                <option value="5">5 ms</option>
                            </select>

                            <label for="voltsDiv">Вольт на деление:</label>
                            <select class="form-control" id="voltsDiv">
                                <option value="1">1 V</option>
                                <option value="2">2 V</option>
                                <option value="5" selected>5 V</option>
                                <option value="10">10 V</option>
                                <option value="25">25 V</option>
                            </select>

                            <label for="offsetX">Сдвиг по горизонтали</label>
                            <input type="range" id="offsetX" min="-500" max="500"/>

                            <label for="offsetY">Сдвиг по вертикали</label>
                            <input type="range" id="offsetY" min="-300" max="300"/>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body p-4">
        <div class="row">
            <div class="col-2">
                <div class="panel panel-default" style="margin-bottom: 0px !important;">
                    <div class="panel-body">
                        <canvas id="data" width="180" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-7 px-0">
                <div class="panel panel-default" style="margin-bottom: 0px !important;">
                    <div class="panel-body">
                        <canvas id="scheme" width="640" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="panel panel-default" style="margin-bottom: 0px !important; height: 100%;">
                    <div class="panel-heading">
                        <h4>Выберете сигналы</h4>
                    </div>
                    <div class="panel-body p-3" id="choose">
                        <?php for ($i = 1; $i <= 2; $i++): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label>Канал <?= $i ?></label>
                                </div>
                                <div class="panel-body p-0">
                                    <div class="container-fluid p-0">
                                        <div class="row p-0 m-0" id="points" channel="<?= $i ?>">
                                            <div class="col m-0 pl-2 pr-1 pt-2 pb-2">
                                                <select class="form-control" id="point" channel="<?= $i ?>" point="1">
                                                    <?php for ($j = 0; $j < 15; $j++): ?>
                                                        <option value="<?= $j ?>"><?= $j ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <div class="col m-0 pl-1 pr-2 pt-2 pb-2">
                                                <select class="form-control" id="point" channel="<?= $i ?>" point="2">
                                                    <?php for ($j = 0; $j < 15; $j++): ?>
                                                        <option value="<?= $j ?>"><?= $j ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row p-0 m-0">
                                            <div class="col p-2 text-center">
                                                <button type="button" class="btn btn-default"
                                                        id="draw_osci" channel="<?= $i ?>">
                                                    Построить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>