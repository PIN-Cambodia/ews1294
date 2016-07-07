$('#province').on('change',function(e){
  console.log(e);
  var pro_id = e.target.value;
  $.get('/disNcom?pro_id='+ pro_id , function(data)
  {
      $('#divdistricts').empty();
      //alert(data);
      $.each(data, function(index, disObj) {
        var CCode2digits = disObj['CCode'];
        if(/^[0-9]*01$/.test(disObj['CCode'])) // Any digits that ending with 01
        {
          $('#divdistricts').append(CCode2digits+'<input type="checkbox" value="'+ disObj['DCode'] +'"/> <span>'+ disObj['DName_kh'] + '</span><br />');
          $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ CCode2digits +'<input type="checkbox" value="'+ disObj['CCode'] +'"/> <span>'+ disObj['CName_kh'] +' ('+ disObj['CName_en'] + ')</span><br />');
        }
        else
        {
            $('#divdistricts').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ CCode2digits +'<input type="checkbox" value="'+ disObj['CCode'] +'"/> <span>'+ disObj['CName_kh'] +' ('+ disObj['CName_en'] + ')</span><br />');
        }
      });
  });

  $.get('http://verboice-cambodia.instedd.org/api/projects/359/reminder_groups.json?id[]=1', function(dataRG)
  {
      // alert(districts);
      $('#rg').empty();
      $.each(dataRG, function(index, rgeach) {
        var nameRG = rgeach['name'];
        $('#rg').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ nameRG + '</span><br />');
      });
  });
});

// $('#reminderGroup').on('change',function(e){
//
//   console.log(e);
//   var rgName = e.target.value;
//   $.get('/getPhonesFromReminderGroup?rg_name='+ rgName , function(data)
//   {
//       //alert(data);
//       //$('#divPhones').empty();
//       $.each(data, function(index, disObj) {
//         alert('a');
//           //$('#divPhones').append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + '<input type="checkbox" value="'+ disObj[0] +'"/> <span>'+ disObj[0] +' ('+ disObj[0] + ')</span><br />');
//       });
//   });

// });
