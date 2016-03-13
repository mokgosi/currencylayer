$(document).ready(function () {
    $(".select2").select2({
        placeholder: "Choose currency",
        allowClear: true
    });

    $(document).on('change', '#form_currency', function () {
        $(this).closest('form').find("input[type=text]").val("");
        var url = Routing.generate('currency_api_get_currency', {'id': $(this).val()});

        $.ajax({
            type: 'GET',
            url: url,
//            data: values,
            success: function (data) {
                console.log(data);
                $('#form_exchangeRate').val(data.exchangeRate);
                $('#form_surchargeRate').val(data.surchargeRate);
                $('#form_additional').val(data.additional);
            }
        });
    });

    $(document).on('keyup', '#form_amountPurchased', function () {

        if (!$('#form_currency').val()) {
            alert('Please select currecy.');
            return;
        }

        var url = Routing.generate('currency_api_get_total');

        var values = {
            'amount': $('#form_amountPurchased').val(),
            'exchangeRate': $('#form_exchangeRate').val(),
            'surchargeRate': $('#form_surchargeRate').val(),
            'additional': $('#form_additional').val()
        };

        $.ajax({
            type: 'GET',
            url: url,
            data: values,
            dataType: 'json',
            success: function (data) {
                $('#form_amountPaid').val(data.amountPaid);
                $('#form_surchargeAmount').val(data.surchargeAmount);
                console.log(data);
            }
        });
    });


});