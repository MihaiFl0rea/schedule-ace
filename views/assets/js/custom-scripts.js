/**
 * Created by Dumbledore on 28.06.2016.
 */
$(document).ready(function() {

    /* Page Authentication */

    // Log in teacher into front interface
    $("#teacherLogin").click(function(event){

        event.preventDefault();

        var name = $("#teacherName").val();
        var password = $("#teacherPassword").val();

        $.post('index.php?m=authentication', {action : 'login', name : name, password : password}, function(response){
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
    $("#teacherLogout").click(function(event) {

        event.preventDefault();

        $.post('index.php?m=authentication', {action : 'logout'}, function(response) {
            if(response == 1) {
                console.log('redirect');
                document.location.href = 'index.php?m=authentication';
            }
        });
    });

    // Exceptions handling (crud)

    $(".container").on('click', '.start-exception', function() {
        var idException = $(this).attr('rel');

        $("#startException"+idException).timepicker({ 'timeFormat': 'G:i' });
    });

    $("#startException").timepicker({ 'timeFormat': 'G:i' });

    $(".container").on('click', '.end-exception', function() {
        var idException = $(this).attr('rel');

        $("#endException"+idException).timepicker({ 'timeFormat': 'G:i' });
    });

    $("#endException").timepicker({ 'timeFormat': 'G:i' });

    $("#showExceptionForm").click(function() {

        $("#showExceptionForm").addClass("hidden");
        $("#addExceptionForm,#hideExceptionForm").removeClass("hidden");

    });

    $("#hideExceptionForm").click(function() {

        $("#showExceptionForm").removeClass("hidden");
        $("#addExceptionForm,#hideExceptionForm").addClass("hidden");

    });

    $(".container").on('click','.edit-form-display', function() {

        var idException = $(this).attr('id');

        $("#displayException"+idException).addClass('hidden');
        $("#editExceptionForm"+idException).removeClass('hidden');

    });

    $(".container").on('click', '.cancel-exception', function() {

        var idException = $(this).attr('id');

        $("#displayException"+idException).removeClass('hidden');
        $("#editExceptionForm"+idException).addClass('hidden');

    });

    $(".container").on('click', '.edit-exception', function(event) {

        event.preventDefault();

        var idException = $(this).attr('id');

        var day = $("#exceptionDay"+idException+" option:selected").val();

        var startException = $("#startException"+idException).val();
        var endException = $("#endException"+idException).val();
        var hours = startException + "-" + endException;

        $.post('index.php?m=functions&case=edit_exception', {idException : idException, day : day, hours : hours}, function(response) {

            if(response == '1') {
                var dayString = '';
                if(day == 1) {
                    dayString = 'Luni';
                } else if(day == 2) {
                    dayString = 'Marti';
                } else if(day == 3) {
                    dayString = 'Miercuri';
                } else if(day == 4) {
                    dayString = 'Joi';
                } else if(day == 5) {
                    dayString = 'Vineri';
                }

                $("#displayException"+idException+" span").text(dayString+' | '+hours);
                $("#startException"+idException).val(startException);
                $("#endException"+idException).val(endException);
                $("#exceptionDay"+idException+" option[value="+day+"]").prop('selected',true);

                $("#displayException"+idException).removeClass('hidden');
                $("#editExceptionForm"+idException).addClass('hidden');
            }

        });

    });

    $("#addException").click(function(event) {

        event.preventDefault();

        var day = $("#exceptionDay option:selected").val();

        var startException = $("#startException").val();
        var endException = $("#endException").val();
        var hours = startException + "-" + endException;

        var teacherId = $("#teacherId").attr('rel');

        $.post('index.php?m=functions&case=add_exception', {idTeacher : teacherId, day : day, hours : hours}, function(response) {

            if(response != '0') {

                $("#showExceptionForm").removeClass("hidden");
                $("#addExceptionForm,#hideExceptionForm").addClass("hidden");

                var dayString = '';
                if(day == 1) {
                    dayString = 'Luni';
                } else if(day == 2) {
                    dayString = 'Marti';
                } else if(day == 3) {
                    dayString = 'Miercuri';
                } else if(day == 4) {
                    dayString = 'Joi';
                } else if(day == 5) {
                    dayString = 'Vineri';
                }

                // compose html output
                var htmlOutput = composeExceptionHtml(response,startException,endException,hours,day,dayString);

                if($(".exceptions-line").length) {

                    $(".exceptions-line").before(htmlOutput);

                } else {
                    var beforeHtml = "<h4>Exceptie/i:</h4>";

                    var afterHtml = "<hr class=\"exceptions-line\"/>";

                    var html = beforeHtml + htmlOutput + afterHtml;

                    $("#addExceptionArea").before(html);
                }

                $("#exceptionDay option[value='']").prop('selected',true);
                $("#startException").val("");
                $("#endException").val("");

            }

        });

    });

    $(".container").on('click', '.delete-exception', function() {

        var idException = $(this).attr('id');

        $.post('index.php?m=functions&case=delete_exception', {id : idException}, function(response) {
            // delete exception row
            $("#displayException"+idException).fadeOut('slow',function() {

                $(this).remove();

            });
            // delete edit form of the exception row deleted above
            $("#editExceptionForm"+idException).fadeOut('slow',function() {

                $(this).remove();

            });

        });

    });

    $("#sendMessage").click(function(e) {
        e.preventDefault();

        var name = $("#teacherName").val();
        var subject = $("#messageSubject").val();
        var message = $("#messageTeacher").val();

        var errors = false;
        // validation
        if(subject == ""){
            errors = true;
            $("#afterMessage").html('<font color="red">Specificati un subiect pentru email!</span>');
            $("#sendersubject").focus();
        }else if(message == ""){
            errors = true;
            $("#afterMessage").html('<font color="red">Introduceti mesajul email-ului!</span>');
            $("#sendermessage").focus();
        }
        // if validation has passed
        if(errors == false) {
            $.ajax({
                type: "POST",
                url: "views/assets/plugins/smtpMail/smartprocess.php",
                data: "sendername="+name+"&sendersubject="+subject+"&sendermessage="+message,
                cache: false,
                success: function(html){
                    if(html == 1){
                        $("#messageSubject,#messageTeacher").val("");
                        $("#afterMessage").html('<font color="green">Mesajul a fost trimis cu succes !</span>');
                    }else{
                        $("#messageSubject,#messageTeacher").val("");
                        $("#afterMessage").html('<font color="red">Ne pare rau dar mesajul nu s-a putut trimite !</span>');
                    }
                }
            });
        }

    });

    /* End - Page Home */

});

    function composeExceptionHtml(idException,startException,endException,hours,day,dayString) {
        var htmlOutput="";
        htmlOutput += "<p id=\"displayException"+idException+"\"><span>"+dayString+" | "+hours+" <\/span> <button class=\"btn btn-info btn-xs edit-form-display\" id=\""+idException+"\">Editeaza<\/button> \/ <button class=\"btn btn-danger btn-xs delete-exception\" id=\""+idException+"\">Sterge<\/button><\/p>";
        htmlOutput += "                            <form id=\"editExceptionForm"+idException+"\" class=\"hidden\">";
        htmlOutput += "                                <div class=\"form-group\">";
        htmlOutput += "                                    <label for=\"exceptionDay"+idException+"\">Zi<\/label>";
        htmlOutput += "                                    <select class=\"form-control\" id=\"exceptionDay"+idException+"\" name=\"exceptionDay\">";
        htmlOutput += "                                        <option value=\"1\"";
        //{% if exception.zi == 'Luni' %} selected=\"selected\" {% endif %}" +
        if(day == '1') {
            htmlOutput += "selected=\"selected\"";
        }
        htmlOutput += "\">Luni<\/option>";
        htmlOutput += "                                        <option value=\"2\"";
        if(day == '2') {
            htmlOutput += "selected=\"selected\"";
        }
        htmlOutput += "\">Marti<\/option>";
        htmlOutput += "                                        <option value=\"3\"";
        if(day == '3') {
            htmlOutput += "selected=\"selected\"";
        }
        htmlOutput += "\">Miercuri<\/option>";
        htmlOutput += "                                        <option value=\"4\"";
        if(day == '4') {
            htmlOutput += "selected=\"selected\"";
        }
        htmlOutput += "\">Joi<\/option>";
        htmlOutput += "                                        <option value=\"5\"";
        if(day == '5') {
            htmlOutput += "selected=\"selected\"";
        }
        htmlOutput += "\">Vineri<\/option>";
        htmlOutput += "                                    <\/select>";
        htmlOutput += "                                <\/div>";
        htmlOutput += "                                <div class=\"form-group\">";
        htmlOutput += "                                    <label for=\"startException"+idException+"\">Intre<\/label>";
        htmlOutput += "                                    <input type=\"text\" class=\"form-control start-exception\" id=\"startException"+idException+"\" rel=\""+idException+"\" value=\""+startException+"\">";
        htmlOutput += "                                <\/div>";
        htmlOutput += "                                <div class=\"form-group\">";
        htmlOutput += "                                    <label for=\"endException"+idException+"\">si<\/label>";
        htmlOutput += "                                    <input type=\"text\" class=\"form-control end-exception\" id=\"endException"+idException+"\" rel=\""+idException+"\" value=\""+endException+"\" \/>";
        htmlOutput += "                                <\/div>";
        htmlOutput += "                                <button type=\"submit\" id=\""+idException+"\" class=\"edit-exception btn btn-default\">Editeaza<\/button>";
        htmlOutput += "                                <button type=\"button\" class=\"btn btn-default cancel-exception\" id=\""+idException+"\">Cancel<\/button>";
        htmlOutput += "                            <\/form>";

        return htmlOutput;
    }