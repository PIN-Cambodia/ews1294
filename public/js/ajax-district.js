$(document).ready(function(){
    var totalNo = 0;
    /*$.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
*/
    $('#province').on('change',function(e){
        console.log(e);
        var pro_id = e.target.value;
        $.get('/disNcom?pro_id='+ pro_id , function(data)
        {
            $('#divdistricts').empty();
            //alert(data);
            $.each(data, function(index, disObj) {
                // alert(data.length);
                var CCode2digits = disObj['CCode'];
                var districtSize = 0;
                if(/^[0-9]*01$/.test(disObj['CCode'])) // Any digits that ending with 01
                {

                    $('#divdistricts').append('<input type="checkbox" value="'+ disObj['DCode'] + '" id="' + disObj['DCode'] + '" class="district"/> <span>'+ disObj['DName'] + '</span><br />');
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +'<input type="checkbox" value="'+ disObj['CCode'] +'" id="' + disObj['CCode'] +'" name=\"' + disObj['CCode']+'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                    // $('#divdistricts').append('<input type="checkbox" value="'+ disObj['DCode'] + '" id="' + disObj['DCode'] + '" class="district"/> <span>'+ disObj['DName_kh'] + '</span><br />');
                    // $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ '<input type="checkbox" value="'+ disObj['CCode'] +'" id="' + disObj['CCode'] +'" name=\"' + disObj['CCode']+'\" class="commune"/> <span>'+ disObj['CName_kh'] +' ('+ disObj['CName_en'] + ')</span><br />');
                }
                else
                {
                    // $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ CCode2digits +'<input type="checkbox" value="'+ disObj['CCode'] +'" id="'+ disObj['CCode'] +'" name=\"'+ disObj['CCode'] +'\" class="commune"/> <span>'+ disObj['CName_kh'] +' ('+ disObj['CName_en'] + ')</span><br />');
                    $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ '<input type="checkbox" value="'+ disObj['CCode'] +'" id="'+ disObj['CCode'] +'" name=\"'+ disObj['CCode'] +'\" class="commune"/> <span>'+ disObj['CName'] +' </span><br />');
                }
            });
        });

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
                        // alert('eachCommune1 is false; TotalNo = '+totalNo);
                        $.get('/numberOfPhones?commune_id='+ disObj['CCode'] , function(NoOfPhoneInThisCommune)
                        {
                            totalNo = totalNo + NoOfPhoneInThisCommune.length;

                            if (index == district_length - 1) {
                                $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                            }
                        });
                        //alert('eachCommune1 was not checked; TotalNo = '+totalNo);
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

                        $.get('/numberOfPhones?commune_id='+ disObj['CCode'] , function(NoOfPhoneInThisCommune)
                        {
                            totalNo = totalNo - NoOfPhoneInThisCommune.length;

                            if ( index == district_length - 1) {
                                $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
                            }
                        });
                        //alert('eachCommune1 was checked; TotalNo = '+totalNo);
                    }
                    else {
                        totalNo = totalNo;
                    }
                    //$('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');

                });
            });
        }
        //alert('eachCommune1 is false; TotalNo = '+totalNo);
        // alert('2. TotalNo = '+totalNo);
        // alert('2A. TotalNo = '+totalNo);

    });

// onClick on commnue

    $(document).on("click",".commune",function(e){
        //alert('3. TotalNo = '+totalNo);
        var testThis = document.getElementById(this.id).checked;
        if(testThis)
        {
            $.get('/numberOfPhones?commune_id='+ this.id , function(data)
            {
                totalNo = totalNo + data.length;
                $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
            });
        }
        else {
            $.get('/numberOfPhones?commune_id='+ this.id , function(data)
            {
                totalNo = totalNo - data.length;
                $('#numberOfPhones').html('<h2>'+ totalNo +'</h2>');
            });
        }
        //alert('4. TotalNo = '+totalNo);
    });


    $('form#uploadForm').on('submit',function(event){
        event.preventDefault();

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
                $.ajax({
                    url: "/add_new_activity?communes=" + communes_selected + "&noOfPhones=" + phones.length,
                    data: formData,
                    dataType: 'json',
                    async: false,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (activityId) {
                        console.log(activityId);
                        formData.append('api_token','C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h');
                        // test.append('contacts','[{"phone":"017696365"}]');
                        formData.append('contacts',JSON.stringify(phones));
                        // formData.append('contacts', '[{"phone":"017696365"},{"phone":"012415734"},{"phone":"010567487"},{"phone":"089737630"},{"phone":"012628979"},{"phone":"011676331"},{"phone":"012959466"}]');

                        formData.append('activity_id',activityId[0]);
                        formData.append('sound_url','https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/'+activityId[1]);
                        // test.append('sound_url','http://ews1294.info/sounds/soundFile_11_24_2016_0953am.mp3');
                        formData.append('no_of_retry',3);
                        formData.append('retry_time', 10);
                        console.log(formData);
                        // ** Trigger calls ** //
                        $.ajax({
                            url: 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload',
                            method: 'POST',
                            data: formData,
                            async: false,
                            // success: function(data) {;                     },
                            error: function(e)
                            {
                                console.log(e);
                            },
                            contentType: false,
                            processData: false
                        });
                    },
                    error: function() {
                        alert('sorry, new activity cannot be inserted (សំុទោស! ទិន្នន័យនេះមិនអាចបញ្ចូលបានទេ។)');
                    },
                });
            },
            error: function(e)
            {
                console.log(e);
            },
            contentType: false,
            processData: false
        });
    });
});

