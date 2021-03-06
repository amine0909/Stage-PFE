//show the name of the student's group if it exists
$(function(){
    $.when(check_group()).done(function(data){
        var i=0;
        var parsed = JSON.parse(data);
        while(i<parsed.length){
            //$("#"+parsed[i]['student']+" .info-student .items #groups_show").html("<a href='' data-toggle='modal' data-target='#show_groups' class='toggle-modal-show' id='show_groups_link'>Show groups</a>")
            $("#"+parsed[i]['student']+" .action .pull-right  #groups_show").html("<a href='' data-toggle='modal' data-target='#show_groups' class='toggle-modal-show' id='show_groups_link'>Voir historiques</a>")
            i=i+1;
        }
    });


    //when user click on modal to show groups and session
    $(document).on("click","#show_groups_link",function(){
        var id_student = $(this).parent().parent().parent().parent().attr("id");


        $.when(get_group_name(id_student)).done(function(data){
            var parsed = JSON.parse(data);
            var i=0;
            //create table
            $("#show_groups .modal-body").html("<table class='table'><thead><tr><th scope='col'>Classe  </th><th scope='col'>Année universitaire</th></tr></thead><tbody></tbody></table>")
            while(i<parsed.length){
              $(".table tbody").html("<tr><td>"+parsed[i]['name']+"</td><td>"+parsed[i]['session']+"</tr>");
              i=i+1;
            }
        });
    });


});

//swal the success session
$(function(){
    if( success == true){
      swal("Super !!","Etudiant a été mise a jour avec success","success");
    }

    if(successAddStudent == true){
        swal("Super !!","Etudiant a été ajouter avec success","success");
    }


});


//when admin want to change student's group
$(function(){
    $(".save_update_group_student").click(function(event){
      $('.Div_Err').removeClass('has-error');
      $(".span_Err").text("");
      event.preventDefault();
      if($("#group_student_edit").val() != ""){
            var student_id = $("#id_student").val();
            var group_name = $("#group_student_edit").val();
            var session = $("#session").text();
            //ajax
            $.post($("#update_Students_Group").attr("action"),{id_student:student_id,groupe_name:group_name,session:session},function(data){
                if(data == "done"){
                    swal("Job done!","Student's group has been updated with success","success");
                }
                if(data == "error"){
                    swal("Error!","This group is not found! Choose one of these groups below ","error");
              }
            });
      }else{
        $('.Div_Err').addClass('has-error');
        $(".span_Err").text("Required field");
      }

    });
});

// ADD SOME FILTER
$(function(){
    $("#student_filter").change(function(){
      $(".student").show();
        let selected = $(this).val();

        if(selected == "accepted"){
          $(".student").not($(".accepted").parent().parent().parent()).hide();
        }

        if(selected == "waiting"){
          $(".student").not($(".waiting").parent().parent().parent()).hide();
        }

        if(selected == "rejected"){
          $(".student").not($(".rejected").parent().parent().parent()).hide();
        }


        if(selected == "all"){
          $(".student").show();
        }
    });
  });





/* FUNCTIONS HERE */
//function check if that student has a group
function check_group(){
    return $.post("./check_group_name",{});
}

//function get group name
function get_group_name(id_student){
    return $.post("./get_group_name",{id_student:id_student});
}
