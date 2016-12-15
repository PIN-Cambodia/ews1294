/**
 * Created by phyrum on 12/14/16.
 */

$(function() {
    var token = $('input[name=_token]').val();

    $('#waiting').waiting({
        className: 'waiting-circles',
        elements: 10,
        radius: 50,
        speed: 120,
        auto: true
    });

    $('#upload_warning_file').hide();
    $('#upload_emergency_file').hide();

    // $('#ss_province_1').change(function () {
    $('.ss_province').change(function () {
        $('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
        //var province_id = $('#ss_province_1').val();
        var province_id = $(this).val();
        console.log('province= ' + province_id);
        if(province_id !='')
        {
            $.ajax({
                type: 'POST',
                url: '/getDistricts',
                data: {_token: token, province_id: province_id},
                cache: false,
                success: function(result)
                {
                    $('.ss_district').html(result).show();
                    $('.ss_commune_div').hide();
                }
            });
        }
        return false;
    });

    /* Show Communes select option */
    // $('#ss_district_1').change(function () {
    $('.ss_district').change(function () {
        $('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
        var distric_id = $(this).val();
        console.log('district= ' + distric_id);
        if(distric_id != '')
        {
            $.ajax({
                type: 'POST',
                url: '/getCommunes',
                data: {_token: token, distric_id: distric_id},
                cache: false,
                success: function(result)
                {
                    $('.ss_commune').html(result);
                    $('.ss_commune_div').show();
                }
            });
        }
        return false;
    });

    $('#change_warning_file').click(function () {
        $('#existing_warning_file').hide();
        $('#upload_warning_file').show();
        return false;
    });

    $('#change_emergency_file').click(function () {
        $('#existing_emergency_file').hide();
        $('#upload_emergency_file').show();
        return false;
    });

    // allow only number in textbox
    $(".numeric").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.keyCode === 190 || e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

});