$(document).ready(function(){
    var totalNo = 0;
    var province_val = $('#province').val();
    // On Document Ready, getting all districts and communes under selected province
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

    // // On Province Change, getting all districts and communes under selected province
    $('#province').on('change',function(e){
        // console.log(e);
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

    // onClick on CheckAll checkbox, total
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
                            //console.log(err);
                        }
                    });
                },
                error: function (err) {
                    // console.log(err);
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
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }
    });

// onClick on district checkbox, total its phone numbers
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
                            url: '/numberOfPhones?commune_id='+ disObj['CCode'] ,
                            method: 'GET',
                            async: false,
                            success: function (NoOfPhoneInThisCommune) {
                                totalNo = totalNo + NoOfPhoneInThisCommune[0].phone;
                                if ( index == district_length - 1) {
                                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                                }
                            },
                            error: function (err) {
                                // console.log(err);
                            }
                        });
                    }
                    else {
                        totalNo = totalNo;
                    }
                });
            });
        }
        // If user un-check this district checkbox, total its phone numbers
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
                            url: '/numberOfPhones?commune_id='+ disObj['CCode'] ,
                            method: 'GET',
                            async: false,
                            success: function (NoOfPhoneInThisCommune) {
                                totalNo = totalNo - NoOfPhoneInThisCommune[0].phone;
                                if ( index == district_length - 1) {
                                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                                }
                            },
                            error: function (err) {
                                // console.log(err);
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

// onClick on commnue checkbox, total its phone numbers
    $(document).on("click",".commune",function(e){
        var testThis = document.getElementById(this.id).checked;
        if(testThis)
        {
            $.ajax({
                url: '/numberOfPhones?commune_id='+ this.id ,
                method: 'GET',
                async: false,
                success: function (NoOfPhoneInThisCommuneSelect) {
                    totalNo = totalNo + NoOfPhoneInThisCommuneSelect[0].phone;
                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }
        else {

            $.ajax({
                url: '/numberOfPhones?commune_id='+ this.id ,
                method: 'GET',
                async: false,
                success: function (NoOfPhoneInThisCommuneDiselect) {
                    totalNo = totalNo - NoOfPhoneInThisCommuneDiselect[0].phone;
                    $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }
    });

    // On Click on Send button, upload sound files and phone contacts, then make a call.
    $('form#uploadForm').on('submit',function(event){
        var noOfPhones = $('#numberOfPhones').text();
        var soundFilePath = $('#soundFile').val();
        if (noOfPhones == 0 || noOfPhones == '') {
            alert('WARNING: No phone numbers in the selected commune(s).\n គ្មានលេខទូរស័ព្ទនៅក្នុងឃុំដែលបានជ្រើសរើសទេ។');
            return false; // prevent reload page
        }
        else {
            if (soundFilePath == '') {
                alert('WARNING: You have not selected sound file yet.\n អ្នកមិនទាន់ភ្ជាប់ឯកសារសម្លេងទេ។');
                return false; // prevent reload page
            }
            else {
                $('#modal_waiting').modal('show');
                event.preventDefault();

                $('#modal_waiting').on('shown.bs.modal', function () {
                    var communes_selected = [];
                    $.each($("input[class='commune']:checked"), function () {
                        communes_selected.push($(this).val());
                    });
                    // ** Pass commune codes to get phone numbers ** //
                    $.ajax({
                        url: '/phoneNumbersSelectedByCommunes?commune_ids=' + communes_selected,
                        method: 'GET',
                        async: false,
                        success: function (phones) {
                            // ** Pass commune codes and the number of phone numbers to get activity id ** //
                            var formData = new FormData($("#uploadForm")[0]);
                            var formDataTwillioAPI = new FormData();
                            var sendSuccss = false;
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
                                    formDataTwillioAPI.append('api_token', 'C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h');
                                    // formData.append('contacts', '[{"phone":"017696365"},{"phone":"012415734"},{"phone":"010567487"},{"phone":"089737630"},{"phone":"012628979"},{"phone":"011676331"},{"phone":"012959466"}]');
                                    formDataTwillioAPI.append('activity_id', activityId[0]);
                                    formDataTwillioAPI.append('sound_url', 'https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/' + activityId[1]);
                                    formDataTwillioAPI.append('no_of_retry', 3);
                                    formDataTwillioAPI.append('retry_time', 10);
                                    // To avoid error timeout while sending more than 10000 phone contacts to Twillio server,
                                    // We splite array of phones into the small blocks of 5000 phones.
                                    // So each time, we send only 5000 phone numbers to ews-twilio.
                                    var phone = [];
                                    var startIndex = 0;
                                    var lengthMax = 5000;
                                    var maxIndex = lengthMax;
                                    for (var i = startIndex; i < phones.length; i++) {
                                        if (startIndex < maxIndex) {
                                            phone.push(phones[i]);
                                            startIndex = i; //4999
                                            if (startIndex === maxIndex - 1 || startIndex === phones.length - 1) {
                                                formDataTwillioAPI.set('contacts', JSON.stringify(phone));
                                                // ** Trigger calls ** //
                                                $.ajax({
                                                    url: 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload',
                                                    method: 'POST',
                                                    data: formDataTwillioAPI,
                                                    async: false,
                                                    success: function (data) {
                                                        sendSuccss = true;
                                                        phone = [];
                                                    }, always: function (data1) {
                                                        // console.log('data1= ' + data1);
                                                    },
                                                    error: function (e) {
                                                        // console.log(e);
                                                    },
                                                    contentType: false,
                                                    processData: false
                                                });
                                                // console.log('sending with maxIndex= '+ maxIndex +' and startIndex = '+startIndex +' and length = '+ phones.length);
                                                maxIndex += lengthMax;
                                            }
                                        }
                                        // else
                                        //     console.log('end with startIndex = '+startIndex);
                                    }

                                    if (sendSuccss)
                                        $(location).attr("href", '/calllogActivity?activID=' + activityId[0]);

                                },
                                error: function (error) {
                                    $('#modal_waiting').modal('hide');
                                    alert('sorry, new activity cannot be inserted (សំុទោស! ទិន្នន័យនេះមិនអាចបញ្ចូលបានទេ។)');
                                },
                            });
                        },
                        error: function (e) {
                            $('#modal_waiting').modal('hide');
                        },
                        contentType: false,
                        processData: false
                    }); // .ajax
                }); // .modal_waiting on('shown.bs.modal')
            }
        } // .if
    }); // .form upload submission
});

