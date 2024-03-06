jQuery(document).ready(function ($) {
    // Function to handle click event on body buttons
    $(".body-btn").click(function () {
        let _this = $(this);
        let quiz_id = _this.data('quiz_id');
        let title = _this.attr('title');

        if (quiz_id != '') {
            jQuery.ajax({
                type: 'post',
                cache: false,
                url: URLs.AJAX_URL,
                data: {
                    action: "qz_quiz_render",
                    quiz_id: quiz_id
                },
                success: function (res) {
                    res = JSON.parse(res);

                    if (res.status === true) {
                        let data = res.data;
                        let template = '';

                        _this.addClass('active');
                        _this.nextAll('a').hide();
                        _this.prevAll('a').hide();
                        $('.experience-remove-content').hide();
                        $('#regForm .tab').remove();
                        $('.step-form').show();
                        $('.step-form>.body-part-head').text(title);

                        template += '<div class="tab" style="display: block;">';
                        template += '<label class="question">' + data.title + '</label>';
                        for (const key in data.options) {
                            if (Object.hasOwnProperty.call(data.options, key)) {
                                const element = data.options[key];
                                template += '<div class="qs-options">';
                                template += '<input type="radio" id="" name="options" value="" data-key="' + key + '" data-redirect="' + element.redirect + '">';
                                template += '<label>' + element.value + '</label>';
                                template += '</div>';
                            }
                        }
                        template += '</div>';

                        $(template).insertBefore($('.nextPrev-btns-main'));

                    }
                    else {
                        alertModal(res.msg, 'error', '');
                    }
                }
            })
        }
        else {
            alertModal('No quiz available', 'error', '');
        }


        // Hide all body buttons
        // $(".body-btn").hide();

        // Show only the clicked button
        // $(this).show();

        // Set class "experience-remove-content" to display none
        // $(".experience-remove-content").hide();

        // Set class "step-form" to display block
        // $(".step-form").show();
    });


    // Next Button Click
    $('#nextBtn').on('click', function () {
        let _this = $(this);
        let key = _this.parents().eq(2).prev().find('input:checked').data('key');
        let redirect = _this.parents().eq(2).prev().find('input:checked').data('redirect');
        let quiz_id = $('.img-btns>a.active').data('quiz_id');

        if (redirect === 'empty') {
            $.ajax({
                type: 'post',
                cache: false,
                url: URLs.AJAX_URL,
                data: {
                    action: "qz_next_question",
                    quiz_id: quiz_id,
                    key: key
                },
                success: function (res) {
                    res = JSON.parse(res);
                    if (res.status === true) {
                        let data = res.data;
                        let template = '';

                        _this.parents().eq(2).prev().hide();

                        template += '<div class="tab" style="display: block;">';
                        template += '<label class="question">' + data.title + '</label>';
                        for (const key in data.options) {
                            if (Object.hasOwnProperty.call(data.options, key)) {
                                const element = data.options[key];
                                template += '<div class="qs-options">';
                                template += '<input type="radio" id="" name="options" value="" data-key="' + key + '" data-redirect="' + element.redirect + '">';
                                template += '<label>' + element.value + '</label>';
                                template += '</div>';
                            }
                        }
                        template += '</div>';

                        $(template).insertBefore(_this.parents().eq(2));
                    } else {
                        alertModal(res.msg, 'error', '');
                    }
                }
            });
        }
        else {
            alertModal('Thankyou', 'success', redirect);
        }
    });


    // Options click
    $(document).on('click', '.qs-options>input', function () {
        let _this = $(this);
        let redirect = _this.data('redirect');

        $('#nextBtn').removeClass('btn_disabled');
        if (redirect !== 'empty') {
            $('#nextBtn').addClass('save_form').text('Submit');
        }
        else {
            $('#nextBtn').removeClass('save_form').text('Next');
        }
    });
});

var currentTab = 0;
showTab(currentTab);

function showTab(n) {

    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == x.length - 1) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }

    fixStepIndicator(n);
}

function nextPrev(n) {

    var x = document.getElementsByClassName("tab");

    if (n == 1 && !validateForm()) return false;

    x[currentTab].style.display = "none";

    currentTab = currentTab + n;

    if (currentTab >= x.length) {

        document.getElementById("regForm").submit();
        return false;
    }

    showTab(currentTab);
}

function validateForm() {

    var x,
        y,
        i,
        valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");

    for (i = 0; i < y.length; i++) {

        if (y[i].value == "") {

            y[i].className += " invalid";

            valid = false;
        }
    }

    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid;
}

function fixStepIndicator(n) {

    var i,
        x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    x[n].className += " active";
}

function alertModal(title, icon, redirect) {

    if (redirect != '') {
        Swal.fire({
            title: title,
            icon: icon
        });
    }
    else {
        Swal.fire({
            title: title,
            icon: icon,
            time: 5000
        }).then(() => {
            window.location.href = redirect;
        });
    }
}