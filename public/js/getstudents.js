/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var datatable = $('#staffTbl').DataTable();
datatable
        .column('6:visible')
        .order('desc')
        .draw();
getStudents();
function getStudents()
{


    $.ajax({
        url: 'getstudents',
        type: "GET",
        dataType: 'json',
        success: function (data) {
            console.log(data);

            datatable.clear().draw();

            if (data.length == 0) {
                console.log("NO DATA!");
            } else {

                var rowNum = 0;
                $.each(data, function (key, value) {
                    
                      if(value.middlename==null){
                        value.middlename='';
                    }
                     if(value.contact_no==null){
                        value.contact_no='';
                    }
                    
                     
                    var name = value.firstname + ' ' + value.middlename + ' ' + value.surname;

                    var j = -1;
                    var r = new Array();
                    r[++j] = '<td>' + value.institution_name + '</td>';
                    r[++j] = '<td> ' + value.firstname + ' ' + value.middlename + ' ' + value.surname + '</td>';
                    r[++j] = '<td>' + value.gender + '</td>';
                    r[++j] = '<td>' + value.email_address + '</td>';
                    r[++j] = '<td>' + value.contact_no + '</td>';
                    r[++j] = '<td>' + value.datecreated + '</td>';
                    r[++j] = '<td><button type="button" onclick="editStudent(\'' + value.code + '\')" class="btn btn-outline-info btn-sm  col-sm-6 btn-edit editBtn" ><i class="fa fa-edit""></i><span class="hidden-md hidden-sm hidden-xs"> </span></</button>\n\
                              <button onclick="deleteStudent(\'' + value.id + '\',\'' + name + '\')" class="btn btn-outline-danger btn-sm  col-sm-6  deleteBtn"  type="button"><i class="fa fa-trash-o""></i><span class="hidden-md hidden-sm hidden-xs"> </span></</button></td>';


                    rowNum = rowNum + 1;


                    rowNode = datatable.row.add(r);
                });

                rowNode.draw().node();
            }

        },
        error: function (jXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });

}

function editStudent(code) {
    window.location = 'getstudents/' + code;
}

function deleteStudent(code, title) {
    console.log(code + title + 'aaaaa');
    $('#code').val(code);
    $('#nameholder').html(title);
    $('#confirmModal').modal('show');
}

$('#deleteStudentForm').on('submit', function (e) {

    e.preventDefault();
    $('input:submit').attr("disabled", true);
    var code = $('#code').val();
    var token = $('#token').val();
    $('#confirmModal').modal('hide');
    $('#loaderModal').modal('show');

    $.ajax({
        url: 'deletestudent/' + code,
        type: "DELETE",
        data: {_token: token},
        success: function (data) {
            console.log(data);
            // $("#loader").hide();
            $('input:submit').attr("disabled", false);
            $('#loaderModal').modal('hide');


            if (data == 0) {
                swal("Success!", "Student Deleted Successfully", "success");
                getStudents();
            } else {
                swal("Error!", "Couldnt Update", "error");
            }
        },
        error: function (jXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });

});
