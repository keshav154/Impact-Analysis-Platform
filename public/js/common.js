//@global var for LP edit page
var lpContTitle = [];
var lpContTitleAR = [];
var lpContDesc = [];
var lpContDescAR = [];
var lpContSrc = [];
var lpContApkPkg = [];
var lpContIsPublic = [];
var lpContIsShowOnHome = [];
var lpContPrice = [];
var lpContPublicImage = [];
var lpContPublicVideo = [];
var lpContExpiryDays = [];
var lpContPublicCountryDist = [];
var enrichContSubject = [];
var enrichContKeywords = [];
$(document).ready(function ($) {
    //remote popup : delegate calls to data-toggle="lightbox"
    $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function (event) {
        event.preventDefault();
        return $(this).ekkoLightbox({
            onShow: function () {
                var dialog = $(this.modal).find('.modal-dialog');
                var ths = this;
                if (ths.options.classes)
                    $('#' + ths.modal_id).addClass(ths.options.classes);
            },
            onShown: function () {
                var dialog = $(this.modal).find('.modal-dialog');
                var ths = this;
                if (window.console) {

                }
                if (ths.options.remote) {
                    var remoteUrl = ths.options.remote;
                    var splitUrl = remoteUrl.split("parentCat=");
                    if (splitUrl[1]) {
                        var parentId = splitUrl[1].charAt(0);
                        if (parentId > 0 && ths.options.rel == 'add_syllabus') {
                            $('.ekko-lightbox:first').remove();
                            $('.modal-backdrop:first').remove();
                        }
                    }
                }
                if (ths.options.width) {
                    var width = ths.options.width;
                    dialog.css('max-width', width);
                }
                if (ths.options.height) {
                    var height = ths.options.height;
                    dialog.css('max-width', height);
                }

                if (ths.options.modallg)
                    $('#' + ths.modal_id).find('.modal-dialog').css({width: '', 'max-width': ''}).addClass("modal-lg");

                if (ths.options.classes)
                    $('#' + ths.modal_id).addClass(ths.options.classes);

                if (ths.options.showcallback) {
                    try {
                        eval(ths.options.showcallback)(ths);
                    } catch (err) {
                        console.error(err);
                    }
                }
            },
            onHide: function () {
                var ths = this;
                if (ths.options.rel == 'pick_category') {
                    $('#add-syllabus-popup').trigger('click');
                }
                if (ths.options.rel == 'add_lesson') {
                    $('#add-lesson-popup').trigger('click');
                }

                if (ths.options.hidecallback) {
                    try {
                        eval(ths.options.hidecallback)(ths);
                    } catch (err) {
                        console.error(err);
                    }
                }
            },
            onNavigate: function (direction, itemIndex) {}
        });
    });
    // Default initialization 
    if ($(".styled").length) {
        $(".styled, .multiselect-container input").uniform({
            radioClass: 'choice'
        });
    }
    // File input
    if ($(".file-styled").length) {
        $(".file-styled").uniform({
            wrapperClass: 'bg-blue',
            fileButtonHtml: '<i class="icon-file-plus"></i>'
        });
    }
    $('.decimal').keyup(function () {
        var val = $(this).val();
        if (isNaN(val)) {
            val = val.replace(/[^0-9\.]/g, '');
            if (val.split('.').length > 2)
                val = val.replace(/\.+$/, "");
        }
        $(this).val(val);
    });

    $(document).on('click', ".getUnCategorized", function () {
        var parent = $(this).closest('.panel-body');
        parent.find('select[name=grade]').val('');
        parent.find('select[name=subject_id]').html('<option value="">' + selectSub + '</option>');
        getFilterData(clicked = true, parent);
        isActive = false;
    });
});

function startLoader(target) {
    $(target).block({
        message: '<i class="icon-spinner2 spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait',
            'box-shadow': '0 0 0 1px #ddd'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
    });
}
//function to form submit using Ajax
function submitForm(formSelector, e, lpContent, mdError, default_async) {
    $('.loader-password').removeClass('hide');
    $('.loader-password').addClass('show');
    e.preventDefault();
    if (typeof default_async == "undefined") {
        default_async = true;
    }

    // code to add client side validation to country filter

    var countryFilter = $('.filter_appended_data').length;
    // this var being used to check for edit content page
    var removed_country_listing = $('.remove-country-linking-with-content').length;
    if ($("#isCountryDist").val() != undefined && $("#isCountryDist").val() == 'yes') {
        setCountryDistValues('content');
    }
    if ($("#isCountryDistCourse").val() != undefined && $("#isCountryDistCourse").val() == 'yes') {
        setCountryDistValues('course');
    }
    var isErrorFoundInCountryDist = false;
    var elementCollection = [];
    var condDescId = $(formSelector + ' ' + "textarea[name*='CONT_DESC']").attr('id');
    var condDescIdAR = $(formSelector + ' ' + "textarea[name*='CONT_DESC_AR']").attr('id');
    if (removed_country_listing === 0 && $('#validation-filter').val() == 'yes') {
        for (var i = 0; i < countryFilter + 1; i++) {
            if ($(formSelector + ' .country_list' + countryFilter).val() === '') {
                setErrorMessage(emptyStandardErrorMsg, true);
                isErrorFoundInCountryDist = true;
            } else if ($(formSelector + ' .grade_list' + countryFilter).val() === '') {
                setErrorMessage(emptyGradeErrorMsg, true);
                isErrorFoundInCountryDist = true;
            } else if ($(formSelector + ' .subject_list' + countryFilter).val() === '') {
                setErrorMessage(emptySubjectErrorMsg, true);
                isErrorFoundInCountryDist = true;
            } else if ($(formSelector + ' .Syllabus_list' + countryFilter).val() === '') {
                setErrorMessage(emptySyllabusErrorMsg, true);
                isErrorFoundInCountryDist = true;
            }
        }
    }
    if ($(formSelector + ' .validation-filter').val() === 'yes') {
        if (removed_country_listing === 0) {
            for (var i = 0; i < countryFilter + 1; i++) {
                if ($(formSelector + ' .grade_list' + i).val() === '') {
                    setErrorMessage(emptyGradeErrorMsg, true);
                    isErrorFoundInCountryDist = true;
                } else if ($(formSelector + ' .subject_list' + i).val() === '') {
                    setErrorMessage(emptySubjectErrorMsg, true);
                    isErrorFoundInCountryDist = true;
                }
                elementCollection.push($(formSelector + ' .subject_list' + i).val());
            }
            var sorted_arr = elementCollection.sort();
            if (!isErrorFoundInCountryDist) {
                for (var i = 0; i < elementCollection.length - 1; i++) {
                    if (sorted_arr[i + 1] === sorted_arr[i]) {
                        setErrorMessage(duplicateSubjectErrorMsg, true);
                        isErrorFoundInCountryDist = true;
                    }
                }
            }
        } else if (removed_country_listing > 0) { // edit subject case
            for (var j = 0; j < removed_country_listing; j++) {
                // create array using li elements for subjects
                elementCollection.push($('.remove-country-linking-with-content').eq(j).find('li').eq(1).attr('id'));
            }
            if (countryFilter > 0) {
                for (var j = 1; j <= countryFilter; j++) {
                    if ($(formSelector + ' .grade_list' + j).val() === '') {
                        setErrorMessage(emptyGradeErrorMsg, true);
                        isErrorFoundInCountryDist = true;
                    } else if ($(formSelector + ' .subject_list' + j).val() === '') {
                        setErrorMessage(emptySubjectErrorMsg, true);
                        isErrorFoundInCountryDist = true;
                    }
                    elementCollection.push($(formSelector + ' .subject_list' + j + ' option:selected').val());
                }
            }
            if (!isErrorFoundInCountryDist) {
                for (var i = 0; i < elementCollection.length; i++) {
                    for (var j = i + 1; j < elementCollection.length; j++) {
                        if (elementCollection[i] === elementCollection[j]) {
                            setErrorMessage(duplicateSubjectErrorMsg, true);
                            isErrorFoundInCountryDist = true;
                        }
                    }
                }
            }
        }
        elementCollection = [];
    }
    if ($(formSelector + ' .validation-coupon').val() === 'yes') {
        isErrorFoundInCountryDist = validateCoupon(formSelector);
    }
    // if no error found during country filteration
    if (isErrorFoundInCountryDist === false) {
        // calling this method if no error found during country filteration
        hideMsg('#displayMsg', true);
        var formObj = $(formSelector);

        var postData = formObj.serializeArray();
        postData = descOfSummernotes(postData, formObj, condDescId, condDescIdAR);
//        if (lpContent) {
//            postData = descOfSummernotes(postData, formObj, condDescId, condDescIdAR);
//        }
        var formURL = formObj.attr("action");
        var mess = '';
        var buttonval = $(formSelector + '-btn').val();
//        if ('SetPassword' == formObj.attr('id'))
//            $('.loader-password').removeClass('hide');
        $.ajax({
            url: formURL,
            type: "POST",
            dataType: "json",
            async: default_async,
            data: postData,
            beforeSend: function () {
                if ($("#loader").length > 0) {
                    startLoader("#loader");
                    window.scrollTo(10, 10);
                }
                $(formSelector + '-btn').val(loadingText);
                $(formSelector + '-btn').attr('disabled', true);
            },

            success: function (data) {
                if ($("#loader").length > 0) {
                    $("#loader").unblock();
                }
                if (formSelector != '#authenticate-user') {
                    $(formSelector + '-btn').val(buttonval);
                    $(formSelector + '-btn').attr('disabled', false);
                }
                $('.elem-err').remove();
                if (!data.data.result) {
                    if (typeof data.data.message === 'object') {
                        jQuery.each(data.data.message, function (k, v) {
                            jQuery.each(v, function (kk, vv) {
                                mess += vv + '\n';
                                $('.loader-password').removeClass('show');
                                $('.loader-password').addClass('hide');
                                $(formSelector + '-btn').val(buttonval);
                                $(formSelector + '-btn').attr('disabled', false);
                            });
                            inpKey = k;
                            if (k == 'csrf') {
                                setErrorMessage(mess);

                                return false;
                            }
                            if (mess != '') {
                                if (data.data.lrContent) { // display warning messages as per tab
                                    // remove active class from all head tab
                                    formObj.prev('ul').find('li').removeClass('active');
                                    // remove active class from all tab pane
                                    formObj.find('.tab-pane').removeClass('active');
                                    // add active class to specific tab pane, where error occurred
                                    formObj.find("[name='" + inpKey + "']").parents('.tab-pane').addClass('active');
                                    // getting id attr of active tab page
                                    var activePane = formObj.find("[name='" + inpKey + "']").parents('.tab-pane').attr('id');
                                    if (activePane != undefined) {
                                        var activeHead = activePane.substr(-6); // getting last char like 1,2,3 to make tab head active
                                    }
                                    // add active class to specific tab head of which tab pane is active
                                    $("#tabShow" + activeHead).addClass('active');
                                }

                                if (inpKey == 'monthyear') {
                                    inpKey = 'monthyear[month]';
                                }
                                if (inpKey == 'SELECT_STANDARD') {
                                    inpKey = 'SELECT_STANDARD[]';
                                }
                                if (inpKey == 'LANG_ID') {
                                    inpKey = 'LANG_ID[]';
                                }

                                if (mdError) {
                                    formObj.find("[name='" + inpKey + "']").mdError(false, mess)
                                }
                                if (formObj.find("[name='" + inpKey + "']").parents('.form-group:first').find('span.elem-err').length == 0) {
                                    formObj.find("[name='" + inpKey + "']").parents('.form-group:first').append('<div class="elem-err text-danger"><span>' + mess + '</span></div>');
                                } else {
                                    formObj.find("[name='" + inpKey + "']").parents('.form-group:first').append('span.elem-err').text(mess);
                                }

                            }
                            mess = '';
                        });
                    } else {


                        if (data.data.target !== undefined) {
                            showCustomMsg(data);
                        } else {
                            if (data.data.youtube) {
                                setErrorMessage(data.data.message, false, 'error-msg-youtube');
                            } else {
                                setErrorMessage(data.data.message, data.data.innerhtml);
                            }
                        }
                    }
                } else {
                    // if it is forgot password response
                    if (data.data.forgotPass) {
                        // set otp & user_id
                        $('#verify_otp').val(data.data.otp);
                        $('#user_id').val(data.data.user_id);
                        // show otp div and hide password reset div
                        $('#login-form').hide();
                        $('#otp_div').show();
                    }

                    $(".body-icon").toggleClass("hide");

                    if (mdError)
                        $(formObj).find("input, select, textarea").each(function () {
                            $(this).mdError(true);

                        });
//                    $(formSelector + '-btn').val(loadingText);
//                    $(formSelector + '-btn').attr('disabled', true);
                    setSuccessMessage(data.data.message, data.data.innerhtml);
                    if (data.data.popup == '1') {
                        $("#myemail").html(data.data.email);
                        $("#" + data.data.id).trigger("click");
                        $("#user_id").val(data.data.user_id);
                        $("#auth-user").attr('action', data.data.setUrl);
                        formObj[0].reset();
//                        return false;
                        setTimeout(function () {
                            redirectToUserAuth();
                        }, 10000);
                    }
                    //add Lp content : show msg and reset form
                    if (formURL.indexOf("learning-resources/add-lr-content") > 0) {
                        resetLpForm(formObj);
                    }
                    if (data.data.current_path_redirect == 'Y') {
                        window.location.href = currentUrl;
                    }
                    //APPEND PAYEMENT HTML
                    if (data.data.html !== '' && data.data.defaultContent == undefined) {
                        var cvvNo = $('#cvvNo').val();
//                        formObj[0].reset();
                        if ($('.payment-list-ajax tbody tr').hasClass('nopayment')) {
                            $('.payment-list-ajax tbody tr.nopayment').remove();
                        }
                        $(".payment-list-ajax tbody").append(data.data.html);
                        $('.stepy-step').find('.button-next').removeClass('disabled').prop("disabled", false);
                        var paymentProfileId = $('input[name=payment_radio]:checked').val();
                        $('#customerPaymentProfileId').val(paymentProfileId);
                        $('#cardCode').val(cvvNo);
                        $("#subscribeform").submit();
                    }
                    //APPEND PAYEMENT HTML
                    if (data.data.responseHtmlData !== '' && data.data.defaultContent == undefined) {
                        $("#shipping-addr-data").html(data.data.responseHtmlData);
                        if (formSelector == '#shippingForm') {
                            $(formSelector + '-btn').val("Add Shipping Address");
                            $(formSelector + '-btn').attr('disabled', false);
                            $("#shippingId").val(data.data.defaultShippingId);
                        }
                    }
                    if (data.data.redirectUrl !== undefined) {
                        // to display course/module subscription success/failure message
                        if (data.data.susbscription) {

                            $('.package-detail-page-message').html("");
                            $('.package-detail-page-message').append('<div class="alert alert-success no-border"><button type="button" class="close" data-dismiss="alert"><i class="icon-close2" ></i><span class="sr-only">Close</span></button><span class="text-semibold">' + data.data.susbscriptionMessage + '</span></div>');
                            if (data.data.stopRedirect) {
                                $('#subscribeform-btn').hide();
                            }
                            setTimeout(function () {
                                if (data.data.stopRedirect) {
                                    $('.alert').addClass('hide');
                                    $('#subscribeform-btn').hide();
                                } else {
                                    window.location.href = data.data.redirectUrl;
                                }

                            }, 2000);
                        } else {
                            if (lpContent == true && !isNaN(formURL.substring(formURL.lastIndexOf("/") + 1, formURL.length))) {
                                var table = $('#uncategorized-content-bank-listing').DataTable();
                                table.draw(false);
                                var table = $('#categorized-content-bank-listing').DataTable();
                                table.draw(false);
                                $('#add_attechment').modal('hide');
                                $('.ekko-lightbox').modal('hide');
                            } else {
                                //setTimeout(function () {
                                window.location.href = data.data.redirectUrl;
                                // }, 2000);
                            }

                        }
                    }
                    if (data.data.defaultContent == "1") {
                        changeToNextTab(data.data.tabId, 'tab' + data.data.conttype_id);
                        $('.tabContent_tab' + data.data.conttype_id).removeClass('disabled');
                        $(formSelector).find("[name='SELECT_LANG_TOPIC_ID']").prop('disabled', false).selectpicker('refresh');
                        $(".content-id").val(data.data.CONT_ID);
                        var table = $('#uncategorized-content-bank-listing').DataTable();
                        table.draw(false);
                        var table = $('#categorized-content-bank-listing').DataTable();
                        table.draw(false);
                    }
                    reapplyUniform();
                }
//                if ('SetPassword' == formObj.attr('id'))
//                    $('.loader-password').addClass('hide');
            },

        });
        e.preventDefault();
        return false;
    } else {
        $('.ekko-lightbox').animate({scrollTop: 0});
        setTimeout(function () {
            $('#displayMsg').find('.alert-danger').fadeOut('slow');
            $('#displayMsg').find('.alert-danger').remove();
        }, 5000);

        e.preventDefault();
    }
}

function verifyOtp() {
    var user_id = $('#user_id').val();
    var verify_otp = $('#verify_otp').val();
    var entered_otp = $('#entered_otp').val();
    if ($('#entered_otp').val() == '') {
        $('#displayOtpError').html('<span class="text-danger">Please enter OTP in text filed.</span>');
    } else if (verify_otp == entered_otp) {
        var resetToken = randomString();
        window.location.href = basepath + '/forgot/resetpassword/' + resetToken + user_id;
    } else {
        $('#displayOtpError').html('<span class="text-danger">OTP not verified. Please enter right OTP.</span>');
    }
}

function randomString() {
    var text = "";
    var possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 6; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

function redirectToUserAuth() {
    $("#auth-user").submit();
}
function validateCoupon(formSelector) {
    if (new Date($(formSelector + ' #start_date_time').val()) >= new Date($(formSelector + ' #expiry_date_time').val()))
    {
        setErrorMessage("Expiry date must be greater then start date", true);
        return true;
    }
    if ($(formSelector + " .distribution:checked").val() == 'P') {
        if ($(".single-checkbox-package:checkbox:checked").size() == 0 && $(".single-checkbox-module:checkbox:checked").size() == 0) {
            setErrorMessage("Please select at least one package or module", true);
            return true;
        }
    }
    return false;
}
function setCountryDistValues(type) {
    var distlist = '';
    if (type == 'content') {
        $("input[name='COUNTRY_CODE_LIST[]']").each(function ()
        {
            if ($(this).is(':checked'))
            {
                distlist += $(this).val() + ",";
            }
        })
        $("input[name='ALL_COUNTRY_SELECTED']").val(distlist.slice(0, -1));
    } else if (type == 'course') {
        $("input[name='COUNTRY_CODE_LIST_COURSE[]']").each(function ()
        {
            if ($(this).is(':checked'))
            {
                distlist += $(this).val() + ",";
            }
        })
        $("input[name='ALL_COUNTRY_SELECTED_COURSE']").val(distlist.slice(0, -1));
    }
}
//function to reset LP content form
function resetLpForm(formObj) {
    formObj[0].reset();
    hideMsg('#displayMsg', true);
    if (formObj.find(".summernote").length)
        formObj.find(".summernote").code('');
    if (formObj.find("#embed-preview").length)
        formObj.find("#embed-preview").html('');
    if (formObj.find("#link-meta-info").length)
        formObj.find("#link-meta-info").html('');
}
//to assign summernote code to description 
function descOfSummernotes(postData, formObj, condDescId, condDescIdAR) {
    $.each(postData, function (key, data) {
        if (this.name == "CONT_SRC") {
            //if (typeof CKEDITOR !== "undefined") {
            console.log($('#link-cont-src').val());
            if ($('#link-cont-src').val() != undefined && $('#link-cont-src').val() !== '') {
                console.log('link src');
                this.value = $('#link-cont-src').val();
            } else if ($('#embed-cont-src').val() != undefined && $('#embed-cont-src').val() !== '') {
                console.log('embed src');
                this.value = $('#embed-cont-src').val();
            } else if (typeof textcontsrc !== "undefined") {
                console.log('text src');
                this.value = CKEDITOR.instances.textcontsrc.getData();
            }
            //}
        }
        if (this.name == "CONT_DESC") {
            if (typeof CKEDITOR !== "undefined") {
                if (typeof condDescId !== "undefined" && condDescId == 'audiocontdesc') {
                    this.value = CKEDITOR.instances.audiocontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'documentcontdesc') {
                    this.value = CKEDITOR.instances.documentcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'embedcontdesc') {
                    this.value = CKEDITOR.instances.embedcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'imagecontdesc') {
                    this.value = CKEDITOR.instances.imagecontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'linkcontdesc') {
                    this.value = CKEDITOR.instances.linkcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'modelcontdesc') {
                    this.value = CKEDITOR.instances.modelcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'textcontdesc') {
                    this.value = CKEDITOR.instances.textcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'videocontdesc') {
                    this.value = CKEDITOR.instances.videocontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'widgetcontdesc') {
                    this.value = CKEDITOR.instances.widgetcontdesc.getData();
                } else if (typeof condDescId !== "undefined" && condDescId == 'apkcontdesc') {
                    this.value = CKEDITOR.instances.apkcontdesc.getData();
                }
            }
        }
        if (this.name == "CONT_DESC_AR") {
            if (typeof CKEDITOR !== "undefined") {
                if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'audiocontdescar') {
                    this.value = CKEDITOR.instances.audiocontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'documentcontdescar') {
                    this.value = CKEDITOR.instances.documentcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'embedcontdescar') {
                    this.value = CKEDITOR.instances.embedcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'imagecontdescar') {
                    this.value = CKEDITOR.instances.imagecontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'linkcontdescar') {
                    this.value = CKEDITOR.instances.linkcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'modelcontdescar') {
                    this.value = CKEDITOR.instances.modelcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'textcontdescar') {
                    this.value = CKEDITOR.instances.textcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'videocontdescar') {
                    this.value = CKEDITOR.instances.videocontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'widgetcontdescar') {
                    this.value = CKEDITOR.instances.widgetcontdescar.getData();
                } else if (typeof condDescIdAR !== "undefined" && condDescIdAR == 'apkcontdescar') {
                    this.value = CKEDITOR.instances.apkcontdescar.getData();
                }
            }
        }

        if (this.name == "QUESTION_TEXT") {
            this.value = formObj.find("#question-text").code();
        }
        if (this.name == "HINT") {
            this.value = formObj.find("#hint").code();
        }
        if (this.name == "ANSWER_EXPLANATION") {
            this.value = formObj.find("#answer-explanation").code();
        }
        if (this.name == "SUBJECT_DESC") {
            this.value = formObj.find("#decription").code();
        }
    });
    return postData;
}
//restrict input is only integer
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
//focus cursor in textbox
function focusTextToEnd(inpObj) {
    inpObj.focus();//I- focus
    var $thisVal = inpObj.val();//II- store input value in a var
    inpObj.val('').val($thisVal);//III- assign blank value then assign store var value
}
// for language change common for all pages 

(function ($) {
    $("a.change_language", document).on('click', function () {
        var getLang = $(this).attr('id');
        var newSet = $(this).attr('rel');
        if ($(this).parent('li').attr('class') == 'active') {
            return false;
        }
        if (getLang) {
            var url = basepath + "/language/" + newSet;
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                success: function (data)
                {
                    window.location.href = currentUrl;
                    return true;
                },
                error: function (error) {

                }
            });
        }
    });
})(jQuery);
//delete data for all controller
function deleteData(deleteActionUrl) {
    if (deleteActionUrl) {
        $.ajax({
            cache: false,
            url: deleteActionUrl,
            success: function (resp) {
                if ($.trim(resp.result) == '1') {
                    if ($.trim(resp.redirectUrl) != '') {
                        window.location.href = resp.redirectUrl;
                    } else if (resp.noRedirect) { // in case of edit image
                        if (resp.message != "" && resp.message != undefined) {
                            $(".errormessage").html(resp.message);
                        } else {
                            // hide model
                            $('#menupage').parents('.ekko-lightbox').modal('hide');
                            // in all other cases  
                            // empty value of image field (hidden)
                            $('.removeImageValue').val("");
                            $('#editImageDiv').hide();
                            $('#addImageDiv').show();
                            $("#edit-thumb-nail").html('');
                            $("#edit-thumb-nail").hide();
                            $("#add-thumb-nail").show();
                        }
                        // this block is for shipping address delete
                        if (resp.responseHtmlData != "") {
                            $("#shipping-addr-data").html(resp.responseHtmlData);
                            $("#shippingId").val(resp.defaultShippingId);
                        }
                    } else {
                        if (deleteActionUrl.indexOf("learning-resources/deleteContentBank") !== -1) {
                            var table = $('#uncategorized-content-bank-listing').DataTable();
                            table.draw(false);
                            var table = $('#categorized-content-bank-listing').DataTable();
                            table.draw(false);
                            $('.ekko-lightbox').modal('hide');
                        } else if (deleteActionUrl.indexOf("deleteByAdmin") !== -1 || deleteActionUrl.indexOf("suspendByAdmin") !== -1) {
                            var table = $('#user-list').DataTable();
                            table.draw(false);
                            $('.ekko-lightbox').modal('hide');
                        } else {
                            window.location.reload();
                        }
                    }
                }
            }
        });
    }
}
// show student registration
function showStudentForm() {
    $("#ut_id").val(1);
    $(".parent").hide();
    $(".student").show();
}
// show parent registration
function showParentForm() {
    $("#ut_id").val(3);
    $(".parent").show();
    $(".student").hide();
}
// use of this function for set success message 
function setSuccessMessage(msg, innerhtml, div) {

    if (typeof div == "undefined") {
        div = "";
    }

    if (!msg)
        return false;
    var strHtml = '<div class="alert alert-success no-border p-10"><button type="button" class="close" data-dismiss="alert"><i class="icon-close2" ></i><span class="sr-only">Close</span></button><span class="text-semibold">' + msg + '</span></div>';
    if (div != "") {
        $('#' + div).html(strHtml);
    } else {
        if (innerhtml) {
            $('#displayMsg').html(strHtml);
        } else {
            var msgLength = $('#displayMsg').find('.alert-success').length;
            if (msgLength == 0)
                $('#displayMsg').prepend(strHtml);
        }
    }
}
// Show custom msg
function showCustomMsg(data) {
    if (!data.data.message)
        return false;
    if (data.data.succes) {
        var strHtml = '<div class="alert alert-success no-border"><button type="button" class="close" data-dismiss="alert"><i class="icon-close2" ></i><span class="sr-only">Close</span></button><span class="text-semibold">' + data.data.message + '</span></div>';
    } else {
        var strHtml = '<div class="alert alert-danger no-border"><button type="button" class="close" data-dismiss="alert"><i class="icon-close2" ></i><span class="sr-only">Close</span></button><span class="text-semibold">' + data.data.message + '</span></div>';
    }
    $(data.data.target).html(strHtml);
}
// use of this function for set error message 
function setErrorMessage(msg, innerhtml, div) {
    if (typeof div == "undefined") {
        div = "";
    }
    if (!msg)
        return false;
    var strHtml = '<div class="alert alert-danger no-border p-10"><button type="button" class="close" data-dismiss="alert"><i class="icon-close2" ></i><span class="sr-only">Close</span></button><span class="text-semibold">' + msg + '</span></div>';
    if (div != "") {
        $('#' + div).html(strHtml);
    } else {
        if (innerhtml) {
            $('#displayMsg').html(strHtml);
        } else {
            var msgLength = $('#displayMsg').find('.alert-danger').length;
            if (msgLength == 0)
                $('#displayMsg').prepend(strHtml);
        }
    }
}
//hide message after some times
function hideMsg(msgId, blankHtml) {
    setTimeout(function () {
        if (blankHtml) {
            $(msgId).html('');
        } else {
            $(msgId).fadeOut('fast');
        }
    }, 5000);
}
//check serach input from header
function checksearchinput() {
    var searchterm = $.trim($('#search').val());
    if (searchterm.length < 1) {
        $('#search').focus();
        return false;
    } else {
        $('#search').val(searchterm);
        return true;
    }
}
function redirectUrl(e) {
    window.location.href = $(e).val();
}
//not in use below codes [working code goes to above this]
function PrintDiv(divToPrint) {
    var divToPrint = document.getElementById(divToPrint);
    var popupWin = window.open('', '_blank');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();
}
function PrintDivStyle(host, divToPrint) {
    var divToPrint = document.getElementById(divToPrint);
    var popupWin = window.open('', '_blank');
    popupWin.document.open();
    popupWin.document.write("<html><head><style>#attdForm { display:none;} .hidden-print { pointer-events: none; } </style><title>Print</title>");
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();
}
function PrintDivWithCss(host, divToPrint) {
    var divToPrint = document.getElementById(divToPrint);
    var popupWin = window.open('', '_blank');
    popupWin.document.open();
    popupWin.document.write('<html><head></head>');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/icons/icomoon/styles.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/bootstrap.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/components.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/core.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/header.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/colors.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/other.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/dashboard_widget.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/circle.css" type="text/css" />');
    popupWin.document.write('<link rel="stylesheet" href="' + host + '/css/footer.css" type="text/css" />');
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();
}
// use to remove country distribution
function removeCountryDistribution(e, contId, subID) {
    if (contId) {
        var url = basepath + "/learning-resources/remove-country?cont_id=" + contId + "&sub_id=" + subID;
        console.log(url);
        $.ajax({
            type: "POST",
            url: url,
            success: function (data) {
                if (data.result) {
                    if (data.redPath != '') {
                        window.location.href = data.redPath;
                    } else {
                        window.location.reload();
                    }
                } else {
                    $('.text-danger').html("Something went wrong, Please try again later!");
                }
            }
        });
    }
}
function saveFile(url) {
    // Get file name from url.
    var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function () {
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
        a.download = filename; // Set the file name.
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        delete a;
    };
    xhr.open('GET', url);
    xhr.send();
}

/*
 * @desc    Used to get grades, subject, syllabus on the
 *          basis of selected  country.
 * @param   current_event   obj
 * @param   request_type    string  (getGrades/getSubjects/getSyllabus)
 * @param: options {selectAttr: "ID of select where options will be insert.", selectLib: "bootstrap | select2 | other" there can be add more options as per reqirements }
 * 
 * 
 * @updated: 12:36 PM 14-Dec-17
 */
//function getContentFilterData(e, request_type, actionType, options = null) {
//    if (typeof actionType == "undefined") {
//        actionType = "";
//    }
//
//    var selected_value = $(e).val();
//    var selected_row = $(e).attr('class').slice(-1);
//
//    /**
//     * If can not get select_row key so that function get the select_row value. 
//     */
//    try {
//        eval(selected_row);
//    } catch (err) {
//        selected_row = options.selectAttr.slice(-1);
//    }
//    // end
//
//
//    if (typeof actionType == "undefined") {
//        actionType = "";
//    }
//
//
//    if (actionType == "classroom") {
//        selected_row = 0;
//        var url = basepath + "/teacher/get-ajax/" + selected_value + "/" + request_type;
//
//    } else {
//        var url = basepath + "/content-bank/get-ajax/" + selected_value + "/" + request_type;
//    }
//    if(options != null){
//        url += "/1"
//    }
//    if (selected_value === '') {
//        if (request_type === 'getGrades') {
//            $("select.grade_list" + selected_row).html('<option value="">' + selectGrade + '</option>');
//            $("select.subject_list" + selected_row).html('<option value="">' + selectSub + '</option>');
//            $("select.Syllabus_list" + selected_row).html('<option value="">' + selectSyl + '</option>');
//        } else if (request_type === 'getSubjects') {
//            $("select.subject_list" + selected_row).html('<option value="">' + selectSub + '</option>');
//            $("select.Syllabus_list" + selected_row).html('<option value="">' + selectSyl + '</option>');
//        } else if (request_type === 'getSyllabus') {
//            $("select.Syllabus_list" + selected_row).html('<option value="">' + selectSyl + '</option>');
//        }
//        if($("#select-picker-refresh").length){
//            $("select.grade_list" + selected_row).selectpicker('refresh');
//            $("select.subject_list" + selected_row).selectpicker('refresh');
//            $("select.Syllabus_list" + selected_row).selectpicker('refresh');
//        }
//        
//    } else {
//        $.ajax({
//            type: "POST",
//            cache: false,
//            async: false,
//            url: url,
//            success: function (data) {
//                if (request_type === 'getGrades') {
//                    $(".grade_list" + selected_row).html(data);
//                    $(".subject_list" + selected_row).html('<option value="">' + selectSub + '</option>');
//                    $(".Syllabus_list" + selected_row).html('<option value="">' + selectSyl + '</option>');
//                } else if (request_type === 'getSubjects') {
//                    if (actionType == "classroom") {
//                        data = "<option value=''>All</option>" + data.substr(data.indexOf("</option>") + 9);
//                    }
//                    $("select.subject_list" + selected_row).html(data);
//                    $("select.Syllabus_list" + selected_row).html('<option value="">' + selectSyl + '</option>');
//
//
//                    /**
//                     * Refreshing select if any library used in select
//                     */
//                    if(options.selectLib === "bootstrap") {
//                        $("select.subject_list" + selected_row).selectpicker('refresh');
//                    }
//                    //end
//                    
//                } else if (request_type === 'getSyllabus') {
//                    $(".Syllabus_list" + selected_row).html(data);
//                }
//            }
//        });
//}
//}

function getHierarchy(e, request_type) {
    var selected_value = $(e).val();
    var selected_row = $(e).closest('.tagging-dropdown');
    var postData = {"id": selected_value, "request_type": request_type};
    var branchContainer = selected_row.find('select[name^=branch]');
    var marketContainer = selected_row.find('select[name^=market]');
    var url = basepath + "/user-management/getBranchNMarket";
    if (selected_value === '') {
        if (request_type === 'branch') {
            branchContainer.html('<option value="">Select Branch</option>');
            marketContainer.html('<option value="">Select Market</option>');
        } else if (request_type === 'market') {
            marketContainer.html('<option value="">Select Market</option>');
        }
    } else {
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            url: url,
            data: postData,
            success: function (data) {
                if (request_type === 'branch') {
                    branchContainer.html(data);
                    marketContainer.empty().html('<option value="">Select Market</option>');
                } else if (request_type === 'market') {
                    marketContainer.empty().html(data);
                }
            }
        });
    }
}

$(document).ready(function () {
    $('[data-toggle="name-link"]').click(function (e) {
        e.preventDefault();
        var t = $(this.hash);
        var t = t.length && t || $('[name=' + this.hash.slice(1) + ']');
        if (t.length) {
            var tOffset = t.offset().top;
            $('html,body').animate({
                scrollTop: tOffset - 55
            }, 'slow');
            return false;
        }
    });
});


// used of this function for re-apply css on radio and checkbox input after ajax request
function reapplyUniform() {
    // Default initialization 
    if ($(".styled").length) {
        $(".styled, .multiselect-container input").uniform({
            radioClass: 'choice'
        });
    }
    // File input
    if ($(".file-styled").length) {
        $(".file-styled").uniform({
            wrapperClass: 'bg-blue',
            fileButtonHtml: '<i class="icon-file-plus"></i>'
        });
    }
}

// -- show hide filter --    
function showFilter() {
    $(".hidefilter").show();
    $(".showfilter").hide();
}
function hideFilter() {
    $(".hidefilter").hide();
    $(".showfilter").show();
}


function themeResponsive() {
    $('.sidebar.sidebar-main').css('height', $(window).height() - $('.nav-container>nav').outerHeight());
    $('.content-wrapper:not(.not-margin-top), .module-title-row').css('margin-top', $('.nav-container>nav').outerHeight());
    $('.sidebar.sidebar-main').css('top', $('.nav-container>nav').outerHeight());
}
$(function () {
    themeResponsive();
    $(window).resize(function () {
        setTimeout(function () {
            themeResponsive();
        }, 100);
    });
    window.onload = themeResponsive;
})

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function deleteCache() {
    var url = basepath + "/configuration-custom/deleteCache";
    $.ajax({
        type: "POST",
        url: url,
        success: function (data) {
//             $('#deleteModal').modal('toggle');
            data = JSON.parse(data);
            setTimeout(function () {
                $('html').animate({scrollTop: 0}, 0);
            }, 200)
            if (data.code == 200) {
                $("#cache").show();
                $("#cache").removeClass("alert-danger");
                $("#cache").addClass("alert-success");
                $("#cache").text(data.msg);
                setTimeout(function () {
                    $("#cache").hide();
                }, 3000)
            } else {
                $("#cache").show();
                $("#cache").removeClass("alert-success");
                $("#cache").addClass("alert-danger");
                $("#cache").text(data.msg);
                setTimeout(function () {
                    $("#cache").hide();
                }, 3000)
            }
        }
    });
}

function submitFormBasicInfo(formSelector, e, lpContent) {
    isErrorFoundInCountryDist = false;
    $(formSelector).find("textarea[name*='CONT_DESC[]']").each(function (e, v) {
        var id = $(this).attr('id');
        $(this).val($("#" + id).code());
        if (e == 0 && v.value == "") {
            setErrorMessage(DESC_VALIDATION, true);
            isErrorFoundInCountryDist = true;
        }
    });
    $(formSelector).find("input[name*='CONT_TITLE[]']").each(function (e, v) {
        if (e == 0 && v.value == "") {
            setErrorMessage(TITLE_VALIDATION, true);
            isErrorFoundInCountryDist = true;
        }
    });

    var langId = $(formSelector).find("select[name*='SELECT_LANG_TOPIC_ID']").val();
    var html = $(formSelector).find("select[name*='SELECT_LANG_TOPIC_ID'] option:selected").html();
    var contTitle = $(formSelector).find("#showTitleDesc_" + langId).find("input[name*='CONT_TITLE[]']").val();
    var contDesc = $(formSelector).find("#showTitleDesc_" + langId).find("textarea[name*='CONT_DESC[]']").val();
    contDesc = contDesc.replace('<p><br></p>', '');
    if (contDesc == "") {
        setErrorMessage(DESC_VALIDATION, true);
        isErrorFoundInCountryDist = true;
    }
    ;
    if (contTitle == "") {
        setErrorMessage(TITLE_VALIDATION, true);
        isErrorFoundInCountryDist = true;
    }
    ;
    if (isErrorFoundInCountryDist === false) {
        $("#displayMsg").html('');
        submitForm(formSelector, e, lpContent)
    }
}

function submitLoginForm(formSelector, e) {
    $('.loader-password').removeClass('hide');
    $('.loader-password').addClass('show');
    e.preventDefault();
    var default_async = true;
    var isErrorFoundInCountryDist = false;
    var elementCollection = [];
    // if no error found during country filteration
    if (isErrorFoundInCountryDist === false) {
        // calling this method if no error found during country filteration
        hideMsg('#displayMsg', true);
        var formObj = $(formSelector);
        var postData = formObj.serializeArray();
        var formURL = formObj.attr("action");
        var mess = '';
        var buttonval = $(formSelector + '-btn').val();
        $.ajax({
            url: formURL,
            type: "POST",
            dataType: "json",
            async: default_async,
            data: postData,
            beforeSend: function () {
                if ($("#loader").length > 0) {
                    startLoader("#loader");
                    window.scrollTo(10, 10);
                }
                $(formSelector + '-btn').val(loadingText);
                $(formSelector + '-btn').attr('disabled', true);
            },
            success: function (data) {
                if ($("#loader").length > 0) {
                    $("#loader").unblock();
                }
                $('.elem-err').remove();
                if (!data.data.result) {
                    if (typeof data.data.message === 'object') {
                        jQuery.each(data.data.message, function (k, v) {
                            jQuery.each(v, function (kk, vv) {
                                mess += vv + '\n';
                                $('.loader-password').removeClass('show');
                                $('.loader-password').addClass('hide');
                                $(formSelector + '-btn').val(buttonval);
                                $(formSelector + '-btn').attr('disabled', false);
                            });
                            inpKey = k;
                            if (k == 'csrf') {
                                setErrorMessage(mess);

                                return false;
                            }
                            if (mess != '') {
                                if (data.data.lrContent) {
                                    // // display warning messages as per tab
                                    // remove active class from all head tab
                                    formObj.prev('ul').find('li').removeClass('active');
                                    // remove active class from all tab pane
                                    formObj.find('.tab-pane').removeClass('active');
                                    // add active class to specific tab pane, where error occurred
                                    formObj.find("[name='" + inpKey + "']").parents('.tab-pane').addClass('active');
                                    // getting id attr of active tab page
                                    var activePane = formObj.find("[name='" + inpKey + "']").parents('.tab-pane').attr('id');
                                    if (activePane != undefined) {
                                        var activeHead = activePane.substr(-6); // getting last char like 1,2,3 to make tab head active
                                    }
                                    // add active class to specific tab head of which tab pane is active
                                    $("#tabShow" + activeHead).addClass('active');
                                }



                                if (formObj.find("[name='" + inpKey + "']").parents('.form-group:first').find('span.elem-err').length == 0) {
                                    formObj.find("[name='" + inpKey + "']").parents('.form-group:first').append('<div class="elem-err text-danger"><span>' + mess + '</span></div>');
                                } else {
                                    formObj.find("[name='" + inpKey + "']").parents('.form-group:first').append('span.elem-err').text(mess);
                                }

                            }
                            mess = '';
                        });
                    } else {
                        setErrorMessage(data.data.message, data.data.innerhtml);
                    }
                } else {
                    if (data.data.isOtp === true) {
                        $("#phone_no_fieldset").addClass('hide').hide();
                        $("#otp_fieldset").removeClass('hide').show();
                        $("#mobile_no").val(data.data.phone_no);
                        $("#form_type").val('otp');
                        $("#login").attr('action', basepath + '/secure/otp-match');
                    } else {
                        $("#phone_no_fieldset").removeClass('hide').show();
                        $("#otp_fieldset").addClass('hide').hide();
                        $("#mobile_no").val('');
                        $("#form_type").val('phone');
                        $("#login").attr('action', basepath + '/secure');
                    }
                    $(".body-icon").toggleClass("hide");

                    setSuccessMessage(data.data.message, data.data.innerhtml);
                    //add Lp content : show msg and reset form
                    if (formURL.indexOf("learning-resources/add-lr-content") > 0) {
                        resetLpForm(formObj);
                    }
                    if (data.data.current_path_redirect == 'Y') {
                        window.location.href = currentUrl;
                    }
                    if (data.data.redirectUrl !== undefined) {
                        window.location.href = data.data.redirectUrl;
                    }
                    reapplyUniform();
                }
            },
        });
        e.preventDefault();
        return false;
    }
}

