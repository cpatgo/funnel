// money.js

function adesk_money_prorate(amount, from, until) {
    var pro  = 0;
    var days = adesk_date_month_days(from);
    var rate = amount / days;
    var i    = ((from - adesk_date_month_first(from)) / adesk_date.ms_day) + 1;

    while (from <= until) {
        if (i > days) {
            i    = 1;
            days = adesk_date_month_days(from);
            rate = amount / days;
        }

        pro  += rate;
        i    += 1;
        from  = new Date(from.valueOf() + adesk_date.ms_day);
    }

    return adesk_money_round(pro);
}

function adesk_money_round(amt) {
    var spl = amt.toString().split(".");

    if (amt < 0)
        return amt.toPrecision(spl[0].length + 1);
    else
        return amt.toPrecision(spl[0].length + 2);
}
