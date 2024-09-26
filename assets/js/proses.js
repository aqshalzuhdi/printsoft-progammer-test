//LOCAL
var base_url = location.origin + "/printsoft-programmertest";

var datanext = 0, dataprev = 0, limit = 10;
var request  = {"filter": {"kywd": ""}};
var act = "", getfunc = "";

var l = $(".ladda-button-submit").ladda();

function ResetRequest() {
    request = {"filter": {"kywd": ""}};
}

function toastrshow(type, title, message, hideAfter = 5000, stack = 5) {
    message = (typeof message !== 'undefined') ?  message : "";
    let toastr = $.toast({
        heading: message,
        text : title,
        icon: type,                     // error, warning, info, success
        showHideTransition : 'slide',   // It can be plain, fade or slide
        allowToastClose : true,         // Show the close button or not
        hideAfter : hideAfter,          // `false` to make it sticky or time in miliseconds to hide after
        stack : stack,                  // `false` to show one stack at a time count showing the number of toasts that can be shown at once
        textAlign : 'left',             // Alignment of text i.e. left, right, center
        position : 'top-right',         // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values to position the toast on page
    })

    return toastr;
}

function GetData(req, action, successfunc) {
    req = (typeof req !== 'undefined') ?  req : "";
    successfunc = (typeof successfunc !== 'undefined') ? successfunc : "";
    act = (action != "") ? action : "listdatahtml";
    $.ajax({
        type: "POST",
        url: base_url + pagename,
        data: {act:act, req:req},
        dataType: "JSON",
        tryCount: 0,
        retryLimit: 3,
        success: function(resp){
            if(resp.IsError) {
                toastrshow("error", resp.ErrMessage, "Error");
            }else{
                successfunc(resp);
            }
        },
        error: function(xhr, textstatus, errorthrown) {
            console.log(xhr);
            toastrshow("warning", "Periksa koneksi internet anda kembali", "Peringatan");
        }
    });
}

function InsertData(selectorform, successfunc, errorfunc) {
    successfunc = (typeof successfunc !== 'undefined') ?  successfunc : "";
    errorfunc = (typeof errorfunc !== 'undefined') ?  errorfunc : "";
    var formdata   = $(selectorform).serialize() +"&act=insertdata";
    var formaction = $(selectorform).attr("action");
    $.ajax({
        type: "POST",
        url: base_url + formaction,
        data: formdata,
        dataType: "JSON",
        tryCount: 0,
        retryLimit: 3,
        beforeSend: function() {
            l.ladda("start");
        },
        success: function(resp){
            if(resp.IsError == false) {
                toastrshow("success", "Data berhasil disimpan", "Success");
                $("#Frm").trigger("reset");

                if(successfunc != "") {
                    successfunc(resp);
                }
            } else {
                toastrshow("error", resp.ErrMessage, "Error");

                if(errorfunc != "") {
                    errorfunc(resp);
                }
            }

            l.ladda("stop");
        },
        error: function(xhr, textstatus, errorthrown) {
            setTimeout(function(){
                l.ladda("stop");
                toastrshow("warning", "Periksa koneksi internet anda kembali", "Peringatan");
            }, 500);
            console.log(xhr);
        }
    });
}

function UpdateData(selectorform, successfunc, errorfunc) {
    successfunc = (typeof successfunc !== 'undefined') ?  successfunc : "";
    errorfunc = (typeof errorfunc !== 'undefined') ?  errorfunc : "";
    var formdata   = $(selectorform).serialize() +"&act=editdata";
    var formaction = $(selectorform).attr("action");
    $.ajax({
        type: "POST",
        url: base_url + formaction,
        data: formdata,
        dataType: "JSON",
        tryCount: 0,
        retryLimit: 3,
        beforeSend: function() {
            l.ladda("start");
        },
        success: function(resp){
            if(resp.IsError == true) {
                toastrshow("error", resp.ErrMessage, "Error");
                if(errorfunc != "") {
                    errorfunc(resp);
                }
            } else {
                toastrshow("success", "Data berhasil disimpan", "Success");
                if(successfunc != "") {
                    successfunc(resp);
                }
            }

            l.ladda("stop");
        },
        error: function(xhr, textstatus, errorthrown) {
            setTimeout(function(){
                l.ladda("stop");
                toastrshow("warning", "Periksa koneksi internet anda kembali", "Peringatan");
            }, 500);
            console.log(xhr);
        }
    });
}

function DeleteData(selectorform, successfunc, errorfunc) {
    successfunc = (typeof successfunc !== 'undefined') ?  successfunc : "";
    errorfunc = (typeof errorfunc !== 'undefined') ?  errorfunc : "";
    var formdata   = $(selectorform).serialize() +"&act=deletedata";
    var formaction = $(selectorform).attr("action");
    $.ajax({
        type: "POST",
        url: base_url + formaction,
        data: formdata,
        dataType: "JSON",
        tryCount: 0,
        retryLimit: 3,
        beforeSend: function() {
        },
        success: function(resp){
            if(resp == 1 || resp == "1") {
                toastrshow("success", "Data berhasil dihapus", "Success");
                $(selectorform).parents(".modal").modal("hide"); //Tutup modal
                if(successfunc != "") {
                    successfunc(resp);
                }
            } else {
                toastrshow("error", "Data gagal dihapus", "Peringatan");
                if(errorfunc != "") {
                    errorfunc();
                }
            }
        },
        error: function(xhr, textstatus, errorthrown) {
            setTimeout(function(){
                $(".modal").modal("hide");
                toastrshow("warning", "Periksa koneksi internet anda kembali", "Peringatan");
            }, 500);
        }
    });
}