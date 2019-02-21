document.addEventListener('DOMContentLoaded', function () {
    var canvas, context
    var currentSettings = $('.circuits-setting'),
        currentPanel = $('.circuits-panel'),
        currentTab = $('.nav-tabs').find('.active')

    var circuitHtml = '<li class="list-group-item circuits-list-item"><ul class="list-group circuit-items">{{INPUT}}</ul><button type="button" class="btn-sm btn-primary circuit-point-add">Добавить точку</button><button type="button" class="btn-sm ml-1 btn-danger circuit-remove">Удалить</button></li>',
        coordinateHtml = '<li class="list-group-item"><div class="form-group"><div class="row"><div class="col"><input type="text" id="circuit-x" class="form-control" placeholder="x"></div><div class="col"><input type="text" id="circuit-y" class="form-control" placeholder="y"></div></div></div></li>',
        elementHtml = '<li class="list-group-item" data-type="{{TYPE}}" data-name="{{NAME}}" data-value="{{VALUE}}" data-vertical="{{VERTICAL}}" data-direction="{{DIRECTION}}" data-x="{{X}}" data-y="{{Y}}"><div class="row"><div class="col-10"><p>{{NAME}}</p></div><div class="col-2"><button type="button" class="btn btn-default btn-sm element-remove"><span class="glyphicon glyphicon-remove"></span></button></div></div></li>',
        textHtml = '<li class="list-group-item" data-value="{{TEXT}}" data-x="{{X}}" data-y="{{Y}}"><div class="row"><div class="col-10"><p>{{TEXT}}</p></div><div class="col-2"><button type="button" class="btn btn-default btn-sm text-remove"><span class="glyphicon glyphicon-remove"></span></button></div></div></li>'

    var html
    var element, name, value, x, y, vertical, direction // для элементов
    var text
    var circuitsToDelete = [], elementsToDelete = [], textsToDelete = []

    canvas = document.getElementById('scheme')
    if (canvas.getContext) {
        context = canvas.getContext('2d')
    }

    // рисуем изначальную схему
    drawScheme()

    // управление вкладками
    $('.change-tab').each(function () {
        $(this).click(function () {
            currentTab.removeClass('active')
            currentTab = $(this)
            currentTab.addClass('active')

            currentSettings.addClass('hidden')
            currentPanel.addClass('hidden')
            switch ($(this).attr('data-tab')) {
                case 'circuit':
                    currentSettings = $('.circuits-setting')
                    currentPanel = $('.circuits-panel')
                    break
                case 'element':
                    currentSettings = $('.elements-setting')
                    currentPanel = $('.elements-panel')
                    break
                case 'text':
                    currentSettings = $('.text-setting')
                    currentPanel = $('.text-panel')
                    break
            }
            currentSettings.removeClass('hidden')
            currentPanel.removeClass('hidden')
        })
    })

    // сохранение
    $('.save').click(function () {
        var circuits = []
        $('.circuits-list').find('.circuits-list-item').each(function () {
            var curCircuit = {}
            var points = []
            var sort = 0

            $(this).find('.circuit-items').find('li').each(function () {
                if ($(this).attr('data-id')) {
                    sort++
                } else {
                    var point = {}
                    point.x = Number($(this).find('#circuit-x').val())
                    point.y = Number($(this).find('#circuit-y').val())
                    points.push(point)
                }
            })

            curCircuit.parentId = $(this).find('.circuit-remove').attr('data-parent-id')
            curCircuit.lastSort = sort
            curCircuit.points = points

            circuits.push(curCircuit)
        })

        var elements = []
        $('.elements-list').find('li').each(function () {
            var curElement = {}
            if (!$(this).find('.element-remove').attr('data-id')) {
                curElement.element = $(this).attr('data-type')
                curElement.name = $(this).attr('data-name')
                curElement.value = $(this).attr('data-value')
                curElement.x = Number($(this).attr('data-x'))
                curElement.y = Number($(this).attr('data-y'))
                curElement.vertical = $(this).attr('data-vertical') === 'true'
                curElement.direction = $(this).attr('data-direction') === 'true'
                elements.push(curElement)
            }
        })

        var texts = []
        $('.text-list').find('li').each(function () {
            var curText = {}
            if (!$(this).find('.text-remove').attr('data-id')) {
                curText.text = $(this).attr('data-value')
                curText.x = Number($(this).attr('data-x'))
                curText.y = Number($(this).attr('data-y'))
                texts.push(curText)
            }
        })

        $.ajax({
            method: 'POST',
            url: location.href,
            data: JSON.stringify({
                'circuits': {
                    'save': circuits,
                    'delete': circuitsToDelete
                },
                'elements': {
                    'save': elements,
                    'delete': elementsToDelete
                },
                'texts': {
                    'save': texts,
                    'delete': textsToDelete
                }
            }),
            success: function () {
                console.log('done')
            }
        })
    })

    // предосмотр
    $('.circuit-preview').click(function () {
        drawScheme()
    })

    // удаление старых контуров
    $('.circuit-remove').each(function () {
        $(this).click(function () {
            var id = Number($(this).attr('data-parent-id'))
            circuitsToDelete.push(id)
            $(this).closest('li').remove()
            drawScheme()
        })
    })

    // удаление старых элементов
    $('.element-remove').each(function () {
        $(this).click(function () {
            var id = Number($(this).attr('data-id'))
            elementsToDelete.push(id)
            $(this).closest('li').remove()
            drawScheme()
        })
    })

    // удаление старых текстов
    $('.text-remove').each(function () {
        $(this).click(function () {
            var id = Number($(this).attr('data-id'))
            textsToDelete.push(id)
            $(this).closest('li').remove()
            drawScheme()
        })
    })

    // добавление точек у существующих контуров
    $('.circuit-point-add').each(function () {
        $(this).click(function () {
            $(this).siblings('.circuit-items').append(coordinateHtml)
        })
    })

    // добавление нового контура
    $('.circuit-add').click(function () {
        html = circuitHtml.replace('{{INPUT}}', coordinateHtml)
        $('.circuits-list').append(html)

        $('.circuits-list').find('.circuits-list-item').last().find('.circuit-point-add').click(function () {
            $(this).siblings('.circuit-items').append(coordinateHtml)
        })

        $('.circuits-list').find('.circuits-list-item').last().find('.circuit-remove').click(function () {
            $(this).closest('.circuits-list-item').remove()
        })
    })

    // добавление элемента
    $('.element-add').click(function () {
        element = $('#element-select').val()
        name = $('#element-name').val()
        value = $('#element-value').val()
        x = Number($('#x-coordinate').val())
        y = Number($('#y-coordinate').val())
        vertical = $('#vertical').is(':checked')
        direction = $('#direction').is(':checked')

        html = elementHtml.replace(/{{NAME}}/g, name).replace('{{TYPE}}', element).replace('{{VALUE}}', value).replace('{{VERTICAL}}', vertical).replace('{{DIRECTION}}', direction).replace('{{X}}', x).replace('{{Y}}', y)
        $('.elements-list').append(html)

        drawScheme()

        $('.element-remove').last().click(function () {
            $(this).closest('li').remove()
            drawScheme()
        })
    })

    // добавление текста
    $('.text-add').click(function () {
        text = $('#text-value').val()
        x = Number($('#text-x').val())
        y = Number($('#text-y').val())

        html = textHtml.replace('{{X}}', x).replace('{{Y}}', y).replace(/{{TEXT}}/g, text)
        $('.text-list').append(html)

        drawScheme()

        $('.text-remove').last().click(function () {
            $(this).closest('li').remove()
            drawScheme()
        })
    })

    // нарисовать всю схему
    function drawScheme() {
        context.clearRect(0, 0, canvas.width, canvas.height)
        drawCircuits()
        drawElements()
        drawTexts()
    }

    // нарисовать элементы по таблице
    function drawElements() {
        $('.elements-list').find('li').each(function () {
            element = $(this).attr('data-type')
            name = $(this).attr('data-name')
            value = $(this).attr('data-value')
            x = Number($(this).attr('data-x'))
            y = Number($(this).attr('data-y'))
            vertical = $(this).attr('data-vertical') === 'true'
            direction = $(this).attr('data-direction') === 'true'
            drawElement()
        })
    }

    // нарисовать контура по таблице
    function drawCircuits() {
        context.beginPath()
        $('.circuits-list').find('.circuits-list-item').each(function () {
            $(this).find('.circuit-items').find('li').each(function (key) {
                x = Number($(this).find('#circuit-x').val())
                y = Number($(this).find('#circuit-y').val())
                if (key === 0) {
                    context.moveTo(x, y)
                } else {
                    context.lineTo(x, y)
                }
            })
        })
        context.stroke()
        context.closePath()
    }

    // нарисовать текст по таблице
    function drawTexts() {
        $('.text-list').find('li').each(function () {
            text = $(this).attr('data-value')
            x = Number($(this).attr('data-x'))
            y = Number($(this).attr('data-y'))
            context.fillText(text, x, y)
        })
    }

    // нарисовать элемент
    function drawElement() {
        switch (element) {
            case 'R':
                drawResistor()
                break
            case 'C':
                drawCapacitor()
                break
            case 'L':
                drawCoil()
                break
        }
    }

    // резистор
    function drawResistor() {
        var width, height

        if (vertical) {
            width = 20
            height = 50
            context.fillText(name, x + 15, y + 8)
        } else {
            width = 50
            height = 20
            context.fillText(name, x - 8, y - 15)
        }

        context.clearRect(x - width / 2, y - height / 2, width, height)
        context.strokeRect(x - width / 2, y - height / 2, width, height)
    }

    // конденсатор
    function drawCapacitor() {
        var width, height

        if (vertical) {
            width = 50
            height = 10
            context.fillText(name, x + 28, y + 6)
        } else {
            width = 10
            height = 50
            context.fillText(name, x - 9, y - 30)
        }

        context.clearRect(x - width / 2, y - height / 2, width, height)
        context.beginPath()

        if (vertical) {
            context.moveTo(x - width / 2, y - height / 2)
            context.lineTo(x + width / 2, y - height / 2)
            context.moveTo(x - width / 2, y + height / 2)
            context.lineTo(x + width / 2, y + height / 2)
        } else {
            context.moveTo(x - width / 2, y + height / 2)
            context.lineTo(x - width / 2, y - height / 2)
            context.moveTo(x + width / 2, y - height / 2)
            context.lineTo(x + width / 2, y + height / 2)
        }
        context.stroke()
        context.closePath()
    }

    // катушка
    function drawCoil() {
        var width, height

        if (vertical) {
            width = 20
            height = 60
            context.fillText(name, x + 9, y + 7)
        } else {
            width = 60
            height = 20
            context.fillText(name, x - 9, y - 20)
        }

        context.clearRect(x - width / 2, y - height / 2, width, height)
        context.beginPath()
        if (vertical) {
            context.arc(x, y + width, width / 2, (-1 / 2) * Math.PI, Math.PI / 2, true)
        } else {
            context.arc(x - height, y, height / 2, 0, Math.PI, true)
        }
        context.stroke()
        context.closePath()
        context.beginPath()
        if (vertical) {
            context.arc(x, y, width / 2, (-1 / 2) * Math.PI, Math.PI / 2, true)
        } else {
            context.arc(x, y, height / 2, 0, Math.PI, true)
        }
        context.stroke()
        context.closePath()
        context.beginPath()
        if (vertical) {
            context.arc(x, y - width, width / 2, (-1 / 2) * Math.PI, Math.PI / 2, true)
        } else {
            context.arc(x + height, y, height / 2, 0, Math.PI, true)
        }
        context.stroke()
        context.closePath()
    }
})
