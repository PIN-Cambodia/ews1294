$(document).ready(function(){
    var totalNo = 0;
    var province_val = $('#province').val();
    if(province_val!='AllProvinces')
    {
        $.get('/disNcom?pro_id='+ province_val , function(data)
        {
            $('#numberOfPhones').empty();
            $('#divcheckall').empty();
            $('#divcheckall').append('<input type="checkbox" value="'+ province_val + '" id="' + province_val + '" class="checkall"/> <span>Check All</span><br />');
            $('#divdistricts').empty();
            var isDistrict = 0;
            var preDis;
            $.each(data, function(index, disObj) {
                var CCode2digits = disObj['CCode'];
                var districtSize = 0;
                if(index>0)
                    preDis = data[index-1]['DCode'];

                if(preDis != data[index]['DCode'])
                {
                    $('#divdistricts').append('<input type="checkbox" value="'+ disObj['DCode'] + '" id="' + disObj['DCode'] + '" class="district"/> <span>'+ disObj['DName'] + '</span><br />');
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+'<input type="checkbox" value="'+ disObj['CCode'] +'" id="' + disObj['CCode'] +'" name=\"' + disObj['CCode']+'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                }
                else{
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+'<input type="checkbox" value="'+ disObj['CCode'] +'" id="'+ disObj['CCode'] +'" name=\"'+ disObj['CCode'] +'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                }
            });
        });
    }

    $('#province').on('change',function(e){
        console.log(e);
        var pro_id = e.target.value;
        $.get('/disNcom?pro_id='+ pro_id , function(data)
        {

            $('#numberOfPhones').empty();
            $('#divcheckall').empty();
            $('#divcheckall').append('<input type="checkbox" value="'+ pro_id + '" id="' + pro_id + '" class="checkall"/> <span>Check All</span><br />');
            $('#divdistricts').empty();
            var isDistrict = 0;
            var preDis;
            $.each(data, function(index, disObj) {
                var CCode2digits = disObj['CCode'];
                var districtSize = 0;
                if(index>0)
                    preDis = data[index-1]['DCode'];

                if(preDis != data[index]['DCode'])
                {
                    $('#divdistricts').append('<input type="checkbox" value="'+ disObj['DCode'] + '" id="' + disObj['DCode'] + '" class="district"/> <span>'+ disObj['DName'] + '</span><br />');
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+'<input type="checkbox" value="'+ disObj['CCode'] +'" id="' + disObj['CCode'] +'" name=\"' + disObj['CCode']+'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                }
                else{
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+'<input type="checkbox" value="'+ disObj['CCode'] +'" id="'+ disObj['CCode'] +'" name=\"'+ disObj['CCode'] +'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                }
            });
        });

    });

    // onClick on CheckAll checkbox
    $(document).on("click",".checkall",function (e) {
        var pro_id = e.target.value;
        var isCheckAll = document.getElementById(this.id).checked;
        if(isCheckAll) {
            $.ajax({
                url: '/disNcom?pro_id='+ pro_id ,
                method: 'GET',
                async: false,
                success: function (allcommunes) {
                    var previousDis;
                    $.each(allcommunes, function(i, eachCommune) {
                        if(i>0)
                            previousDis = allcommunes[i-1]['DCode'];
                        if(previousDis != allcommunes[i]['DCode'])
                        {
                            document.getElementById(eachCommune['DCode']).checked = true;
                            document.getElementById(eachCommune['CCode']).checked = true;
                        }
                        else
                            document.getElementById(eachCommune['CCode']).checked = true;
                    });

                    $.ajax({
                        url: '/checkall?pro_id='+ pro_id ,
                        method: 'GET',
                        async: false,
                        success: function (NoOfPhoneInThisProvince) {
                            totalNo = NoOfPhoneInThisProvince[0].phone;
                            $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        else
        {
            $.ajax({
                url: '/disNcom?pro_id='+ pro_id ,
                method: 'GET',
                async: false,
                success: function (allcommunes) {
                    var previousDis;
                    $.each(allcommunes, function(i, eachCommune) {
                        if(i>0)
                            previousDis = allcommunes[i-1]['DCode'];
                        if(previousDis != allcommunes[i]['DCode'])
                        {
                            document.getElementById(eachCommune['DCode']).checked = false;
                            document.getElementById(eachCommune['CCode']).checked = false;
                        }
                        else
                            document.getElementById(eachCommune['CCode']).checked = false;
                    });
                    totalNo = 0;
                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                    //$('#numberOfPhones').empty();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });

// onClick on district


    $(document).on("click",".district",function(e){

        // using AJAX to select count the number of communes under this district.
        var testThis = document.getElementById(this.id).checked;
        if(testThis)
        {
            $.get('/numberOfcommunes?district_id='+ this.id , function(data)
            {
                var district_length = data.length;

                $.each(data, function(index, disObj) {
                    var eachCommune1 = document.getElementById(disObj['CCode']).checked;
                    document.getElementById(disObj['CCode']).checked = true;
                    if(!eachCommune1)
                    {
                        $.ajax({
                            url: '/numberOfPhonesUpdate?commune_id='+ disObj['CCode'] ,
                            method: 'GET',
                            async: false,
                            success: function (NoOfPhoneInThisCommune) {
                                totalNo = totalNo + NoOfPhoneInThisCommune[0].phone;
                                if ( index == district_length - 1) {
                                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                                }
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    }
                    else {
                        totalNo = totalNo;
                    }
                });
            });

        }
        // If user un-check this district
        else {
            $.get('/numberOfcommunes?district_id='+ this.id , function(data)
            {
                var district_length = data.length;

                $.each(data, function(index, disObj) {
                    // For each commune in this district
                    var eachCommune2 = document.getElementById(disObj['CCode']).checked;
                    document.getElementById(disObj['CCode']).checked = false;
                    if(eachCommune2)
                    {
                        $.ajax({
                            url: '/numberOfPhonesUpdate?commune_id='+ disObj['CCode'] ,
                            method: 'GET',
                            async: false,
                            success: function (NoOfPhoneInThisCommune) {
                                totalNo = totalNo - NoOfPhoneInThisCommune[0].phone;
                                if ( index == district_length - 1) {
                                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                                }
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    }
                    else {
                        totalNo = totalNo;
                    }
                });
            });
        }
    });

// onClick on commnue

    $(document).on("click",".commune",function(e){
        var testThis = document.getElementById(this.id).checked;
        if(testThis)
        {
            $.ajax({
                url: '/numberOfPhonesUpdate?commune_id='+ this.id ,
                method: 'GET',
                async: false,
                success: function (NoOfPhoneInThisCommuneSelect) {
                    totalNo = totalNo + NoOfPhoneInThisCommuneSelect[0].phone;
                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        else {

            $.ajax({
                url: '/numberOfPhonesUpdate?commune_id='+ this.id ,
                method: 'GET',
                async: false,
                success: function (NoOfPhoneInThisCommuneDiselect) {
                    totalNo = totalNo - NoOfPhoneInThisCommuneDiselect[0].phone;
                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });
    
    $('form#uploadForm').on('submit',function(event){
        $('#modal_waiting').modal('show');

        event.preventDefault();
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        //     }
        // });

        $('#modal_waiting').on('shown.bs.modal', function() {
            var communes_selected = [];
            $.each($("input[class='commune']:checked"), function(){
                communes_selected.push($(this).val());
            });

            // ** Pass commune codes to get phone numbers ** //
            $.ajax({
                url: '/phoneNumbersSelectedByCommunes?commune_ids=' + communes_selected,
                method: 'GET',
                async: false,
                success: function(phones) {
                    // ** Pass commune codes and the number of phone numbers to get activity id ** //
                    var formData = new FormData($("#uploadForm")[0]);
                    var formDataTwillioAPI = new FormData();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        url: "/add_new_activity?communes=" + communes_selected + "&noOfPhones=" + phones.length,
                        data: formData,
                        dataType: 'json',
                        async: false,
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        success: function (activityId) {
                            //console.log(activityId);
                            formDataTwillioAPI.append('api_token','C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h');
                             // formDataTwillioAPI.append('contacts','[{"phone":"017696365"}]');
                            // formDataTwillioAPI.append('contacts','[{"phone":"0965537007"}]');
                            // formDataTwillioAPI.append('contacts','[{"phone":"089555127"}]');
                            formDataTwillioAPI.append('contacts',JSON.stringify(phones));
                            // formData.append('contacts', '[{"phone":"017696365"},{"phone":"012415734"},{"phone":"010567487"},{"phone":"089737630"},{"phone":"012628979"},{"phone":"011676331"},{"phone":"012959466"}]');

                            formDataTwillioAPI.append('activity_id',activityId[0]);
                            formDataTwillioAPI.append('sound_url','https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/'+activityId[1]);
                            // test.append('sound_url','http://ews1294.info/sounds/soundFile_11_24_2016_0953am.mp3');
                            formDataTwillioAPI.append('no_of_retry',3);
                            formDataTwillioAPI.append('retry_time', 10);
                            // console.log('twillio=' + formDataTwillioAPI);
                            // ** Trigger calls ** //
                            $.ajax({
                                url: 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload',
                                method: 'POST',
                                timeout: 600000,
                                data: formDataTwillioAPI,
                                success: function(data) {
                                    $('#modal_waiting').modal('hide');
                                    $(location).attr("href", '/calllogActivity?activID=' + activityId[0]);
                                },
                                error: function(e)
                                {
                                    $('#modal_waiting').modal('hide');
                                },
                                contentType: false,
                                processData: false
                            });
                        },
                        error: function(error) {
                            $('#modal_waiting').modal('hide');
                            alert('sorry, new activity cannot be inserted (សំុទោស! ទិន្នន័យនេះមិនអាចបញ្ចូលបានទេ។)');
                            //console.log(error)
                        },
                    });
                },
                error: function(e)
                {
                    $('#modal_waiting').modal('hide');
                    //console.log(e);
                },
                contentType: false,
                processData: false
            });
        });
    });

});

