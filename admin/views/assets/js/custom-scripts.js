/**
 * Created by Dumbledore on 08.03.2016.
 */

$(document).ready(function(){

    /* Page Authentication */

    // Display register area
    $("#registerUser").click(function(event){

        event.preventDefault();

        $("#registerArea").removeClass("hidden");
        $("#loginArea").addClass("hidden");

    });

    // Display login area
    $("#loginUser").click(function(event){

        event.preventDefault();

        $("#registerArea").addClass("hidden");
        $("#loginArea").removeClass("hidden");

    });

    // Log in admin into admin panel
    $("#adminLogin").click(function(event){

        event.preventDefault();

        var pass = $("#login_password").val();
        var email = $("#login_email").val();

        $.post('index.php?m=authentication', {action : 'login', password : pass, email : email}, function(response){
            if(response == '') {
                document.location.href = 'index.php?m=home';
            } else {
                $('.login-errors').html(response);
            }
        });

    });

    /* End - Page Authentication */

    // ----------------------------------------------------------------

    /* Page Home */

    // Log out admin from admin panel
    $("#adminLogout").click(function(event) {

        event.preventDefault();

        $.post('index.php?m=authentication', {action : 'logout'}, function(response) {
           if(response == 1) {
               document.location.href = 'index.php?m=authentication';
           }
        });
    });

    /* End - Page Home */

    // ----------------------------------------------------------------

    /* Page Groups */

    // Display area
    $("#displayArea").click(function(){
       $("#addSubgroupForm,#hideArea").removeClass("hidden");
        $(this).addClass("hidden");
    });

    // Hide area
    $("#hideArea").click(function(){
        $("#addSubgroupForm").addClass("hidden");
        $("#displayArea").removeClass("hidden");
        $(this).addClass("hidden");
    });

    // Add subgroup
    $("#addSubgroup").click(function(event){
        event.preventDefault();

        var name = $("#subgroupName").val();
        var groupId = $("#groupYearId").val();

        $.post('index.php?m=functions&case=add_subgroup', {name : name, id : groupId}, function(response) {

            $("#subgroupName").val("");
            $("#addSubgroupForm,#hideArea").addClass("hidden");
            $("#displayArea").removeClass("hidden");

            var data = jQuery.parseJSON(response);

            var htmlOutput="";
            htmlOutput += "<tr id=\"showSubgroup"+data['id_specializare_an_subgrupa']+"\" >";
            htmlOutput += "<td class=\"align-center\" id=\"subgroupName"+data['id_specializare_an_subgrupa']+"\">"+data['nume']+"<\/td>";
            htmlOutput += "<td>";
            htmlOutput += "<button type=\"button\" class=\"btn btn-sm btn-warning warning_44 edit-subgroup\" id=\""+data['id_specializare_an_subgrupa']+"\">Edit<\/button> | <button type=\"button\" class=\"btn btn-sm btn-danger delete-subgroup\" id=\""+data['id_specializare_an_subgrupa']+"\">Delete<\/button>";
            htmlOutput += "<\/td>";
            htmlOutput += "<\/tr>";
            htmlOutput += "<tr class=\"hidden\" id=\"showEditForm"+data['id_specializare_an_subgrupa']+"\" >";
            htmlOutput += "<td>";
            htmlOutput += "<form>";
            htmlOutput += "<input type=\"text\" class=\"edit-subgroup-name align-center\" id=\"editName"+data['id_specializare_an_subgrupa']+"\" value=\""+data['nume']+"\" \/>";
            htmlOutput += "<\/form>";
            htmlOutput += "<\/td>";
            htmlOutput += "<td><button type=\"button\" class=\"btn btn-sm btn-info warning_44 update-subgroup\" id=\""+data['id_specializare_an_subgrupa']+"\">Update<\/button> | <button type=\"button\" class=\"btn btn-sm btn-success cancel-subgroup\" id=\""+data['id_specializare_an_subgrupa']+"\">Cancel<\/button><\/td>";
            htmlOutput += "<\/tr>";

            $("#subgroupsTable").append(htmlOutput);
            $("p.align-center").remove();


        });

    });

    // Edit button
    $("table").on('click','.edit-subgroup',function() {

        var idSubgroup = $(this).attr('id');

        $("#showSubgroup"+idSubgroup).addClass('hidden');
        $("#showEditForm"+idSubgroup).removeClass('hidden');

    });

    // Cancel button
    $("table").on('click','.cancel-subgroup',function() {

        var idSubgroup = $(this).attr('id');

        $("#showSubgroup"+idSubgroup).removeClass('hidden');
        $("#showEditForm"+idSubgroup).addClass('hidden');

    });

    // Update button
    $("table").on('click','.update-subgroup',function() {

        var idSubgroup = $(this).attr('id');
        var name = $("#editName"+idSubgroup).val();

        $.post('index.php?m=functions&case=edit_subgroup', {id : idSubgroup, name : name}, function(response) {

            var data = jQuery.parseJSON(response);

            $("#subgroupName"+idSubgroup).html(data.nume);

            $("#showSubgroup"+idSubgroup).removeClass('hidden');
            $("#showEditForm"+idSubgroup).addClass('hidden');

        });

    });

    // Delete button
    $("table").on('click','.delete-subgroup',function() {

        var idSubgroup = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_subgroup', {id : idSubgroup}, function(response) {

            $("#showSubgroup"+idSubgroup).fadeOut('slow',function() {

                $(this,"#showEditForm"+idSubgroup).remove();

            });

        });

    });

    /* End - Page Groups */

    // ----------------------------------------------------------------

    /* Page Teachers */

    $(".delete-teacher").click(function() {

        var idTeacher = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_teacher', {id : idTeacher}, function(response) {

            $("#showTeacher"+idTeacher).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    $(".delete-teacher-class").click(function() {

        var idTeacherClass = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_teacher_class', {id : idTeacherClass}, function(response) {

            $("#showTeacherClass"+idTeacherClass).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    /* End - Page Teachers */

    // ----------------------------------------------------------------

    /* Page Halls */

    $(".delete-hall").click(function() {

        var idHall = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_hall', {id : idHall}, function(response) {

            $("#showHall"+idHall).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    $("#teacherClass").select2();

    /* End - Page Halls */

    // ----------------------------------------------------------------

    /* Page Classes */

    $("#classDedicatedHall").select2();
    $("#dedicatedHallsSelect").hide();

    $("#classHall").on('change',function(){

        var currentOption = $(this).val();

        if(currentOption == 5) {

            $("#dedicatedHallsSelect,#dedicatedHallsSelectEdit").show();

        } else {

            $("#dedicatedHallsSelect,#dedicatedHallsSelectEdit").hide();

        }

    });

    $(".delete-class").click(function() {

        var idClass = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_class', {id : idClass}, function(response) {

            $("#showClass"+idClass).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    /* End - Page Classes */

    // ----------------------------------------------------------------

    /* Page Group Classes */

    $("#classId").select2();

    $("#groupAsigned").select2();

    $(".delete-groupClass").click(function() {

        var idgroupClass = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_groupClass', {id : idgroupClass}, function(response) {

            $("#showGroupClass"+idgroupClass).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    /* End - Page Group Classes */

    // ----------------------------------------------------------------

    /* Page Exceptions */

    $("#startException").timepicker({ 'timeFormat': 'G:i' });

    $("#endException").timepicker({ 'timeFormat': 'G:i' });

    $(".delete-exception").click(function() {

        var idException = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_exception', {id : idException}, function(response) {

            $("#showException"+idException).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    /* End - Page Exception */

    // ----------------------------------------------------------------

    /* Page group-exceptions */

    $(".delete-group-exception").click(function() {

        var idGroupException = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_group_exception', {id : idGroupException}, function(response) {

            $("#showGroupException"+idGroupException).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    /* End - Page Exception */

    /* Page Schedule */

    $(".regenerate-schedule").click(function() {

        $.post('index.php?m=functions&case=delete_schedule', function() {

            document.location.href = 'index.php?m=schedule&case=activeGroups';

        });

    });

    /* End - Page Schedule */

});