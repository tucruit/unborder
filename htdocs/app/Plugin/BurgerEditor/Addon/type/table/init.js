"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Table', {
    beforeOpen: function (editorDialog, _, data) {
        var _a, _b;
        if (!data) {
            return;
        }
        var $base = editorDialog.$el.find('[data-bge-table-base]');
        var $baseRow = $base.find('tr');
        var $input = editorDialog.$el.find('[data-bge-table-input]');
        var $inputBody = $input.find('tbody');
        var i = 0;
        for (var _i = 0, _c = Object.keys(data); _i < _c.length; _i++) {
            var key = _c[_i];
            // "td-"で開始するkeyだけ回す
            if (key.indexOf('td-') === 0) {
                var thKey = "th-" + i;
                var tdKey = "td-" + i;
                var thValue = BgE.Util.br2nl((_a = data[thKey]) !== null && _a !== void 0 ? _a : '');
                var tdValue = BgE.Util.br2nl((_b = data[tdKey]) !== null && _b !== void 0 ? _b : '');
                data[thKey] = thValue;
                data[tdKey] = tdValue;
                var $cloneRow = $baseRow.clone();
                $cloneRow.find('[name="th"]').attr('name', "bge-th-" + i);
                $cloneRow.find('[name="td"]').attr('name', "bge-td-" + i);
                $cloneRow.appendTo($inputBody);
                i += 1;
            }
        }
    },
    // 完了前イベント
    beforeChange: function (data, type) {
        // 書き込み先を準備
        var rowNum = 0;
        for (var _i = 0, _a = Object.keys(data); _i < _a.length; _i++) {
            var key = _a[_i];
            // "td-"で開始するkeyだけ回す
            if (key.indexOf('td-') === 0) {
                rowNum += 1;
            }
        }
        var $targetTbl = $(type.el).find('tbody');
        var rows = '';
        for (var i = 0; i < rowNum; i++) {
            rows += "\n\t\t\t\t<tr>\n\t\t\t\t\t<th class=\"bge-type-table__heading\" data-bge=\"th-" + i + "\"></th>\n\t\t\t\t\t<td class=\"bge-type-table__text\" data-bge=\"td-" + i + "\"></td>\n\t\t\t\t</tr>\n\t\t\t";
        }
        $targetTbl.html(rows);
    },
    // 入力・選択終了時イベント
    change: function (_, type) {
        $(type.el)
            .find('[data-bge]')
            .each(function (_, el) {
            $(el).html(function (_, html) {
                return BgE.Util.nl2br(html);
            });
        });
    },
    migrateElement: function (values, type) {
        var _a, _b;
        var $el = $(type.el);
        var $tbody = $el.find('tbody');
        var $rowOrigin = $tbody.find('tr').clone();
        var keys = Object.keys(values);
        $tbody.empty();
        for (var _i = 0, keys_1 = keys; _i < keys_1.length; _i++) {
            var key = keys_1[_i];
            if (!/^th-[0-9]+$/.test(key)) {
                continue;
            }
            var n = parseInt((_b = (_a = key.match(/[0-9]+$/)) === null || _a === void 0 ? void 0 : _a[0]) !== null && _b !== void 0 ? _b : '', 10);
            if (isNaN(n)) {
                continue;
            }
            var thVal = values["th-" + n];
            if (thVal == null || Array.isArray(thVal) || $.isFunction(thVal)) {
                continue;
            }
            var tdVal = values["td-" + n];
            if (tdVal == null || Array.isArray(tdVal) || $.isFunction(tdVal)) {
                continue;
            }
            values["th-" + n] = BgE.Util.nl2br("" + thVal);
            values["td-" + n] = BgE.Util.nl2br("" + tdVal);
            var $row = $rowOrigin.clone();
            $row.find('[data-bge^="th-"]')
                .attr('data-bge', "th-" + n)
                .html(values["th-" + n] + '');
            $row.find('[data-bge^="td-"]')
                .attr('data-bge', "td-" + n)
                .html(values["td-" + n] + '');
            $tbody.append($row);
        }
    },
    customFunctions: {
        // 追加ボタンクリック時イベント
        addRow: function (e, editorDialog, type, module) {
            var $this = $(e.target);
            var $baseTbl = editorDialog.$el.find('[data-bge-table-base]');
            var nextRowNum = editorDialog.$el.find('[data-bge-table-input] tr').length;
            $baseTbl.find('th [data-bge-title]').attr('name', 'bge-th-' + nextRowNum);
            $baseTbl.find('td [data-bge-text]').attr('name', 'bge-td-' + nextRowNum);
            $this.parents('tr').after($baseTbl.find('tbody').html());
            // bind対象外の名称にする
            $baseTbl.find('th [data-bge-title]').attr('name', 'th');
            $baseTbl.find('td [data-bge-text]').attr('name', 'td');
            module.fire('refreshRow', editorDialog, type, module);
            return false;
        },
        // 削除ボタンクリック時イベント
        removeRow: function (e, editorDialog, type, module) {
            if (confirm('削除します。よろしいですか？')) {
                if (editorDialog.$el.find('[data-bge-table-input] tr').length === 1) {
                    alert('全て削除する場合はブロック要素を削除してください');
                    return false;
                }
                // tslint:disable-next-line:only-arrow-functions
                $(e.target)
                    .parents('tr')
                    .fadeOut(200, function () {
                    // tslint:disable-next-line:no-invalid-this
                    $(this).remove();
                    module.fire('refreshRow', editorDialog, type, module);
                });
            }
            return false;
        },
        // 移動ボタンクリック時イベント
        replaceRow: function (e) {
            var $thisTr = $(e.target).parents('tr');
            var $nextTr = $thisTr.next();
            if ($nextTr.length && $thisTr.next().get(0).nodeName.toLowerCase() === 'tr') {
                var thVal = $thisTr.find('th [data-bge-title]').val();
                var tdVal = $thisTr.find('td [data-bge-text]').val();
                $thisTr.find('th [data-bge-title]').val($nextTr.find('th [data-bge-title]').val());
                $thisTr.find('td [data-bge-text]').val($nextTr.find('td [data-bge-text]').val());
                $nextTr.find('th [data-bge-title]').val(thVal);
                $nextTr.find('td [data-bge-text]').val(tdVal);
            }
            else {
                alert('対象が見つかりません');
            }
            return false;
        },
        // 入力エリアのnameを再構築する
        refreshRow: function (_, editorDialog) {
            var $targetTable = editorDialog.$el.find('[data-bge-table-input]');
            $targetTable.find('tr').each(function (i, el) {
                $(el).find('th [data-bge-title]').attr('name', "bge-th-" + i);
                $(el).find('td [data-bge-text]').attr('name', "bge-td-" + i);
            });
        },
    },
});
