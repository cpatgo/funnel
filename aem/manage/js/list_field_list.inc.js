{jsvar name=relid var=$relid}

{literal}
function update_order(ary) {
    var ids     = "";
    var orders  = "";

    for (var i = 0; i < ary.length; i++) {
        ids     += ary[i].toString();
        orders  += i.toString();

        if (i < ary.length - 1) {
            ids     += ",";
            orders  += ",";
        }
    }

    adesk_ajax_call_cb('awebdeskapi.php', 'list.list_field_order', cb_update_order, relid, ids, orders);
}

function cb_update_order(res, xml) {
    document.getElementById('save_order').disabled = true;
}
{/literal}
