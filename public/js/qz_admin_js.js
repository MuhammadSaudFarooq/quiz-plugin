jQuery(document).ready(function ($) {

    /**
     * Questions JS 
     * 
     * */

    // Multiple options listing
    let prev_options = '';
    $('#question-type input').on('change', function () {
        let _this = $(this);
        let type = _this.val();

        if (type === 'multiple') {
            if (prev_options) {
                _this.parent().parent().append(prev_options);
            }
            else {
                _this.parent().parent().append(`
                    <div class="multiple-options">
                        <label>
                            <input type="text" name="multiple_options[]" value="" required />
                        </label>
                        <label>
                            <input type="text" name="multiple_options[]" value="" required />
                        </label>
                        <div class="add-question">
                            <a href="javascript:void(0)">Add New <i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                `);
            }
        }
        else {
            prev_options = _this.parent().parent().find('.multiple-options').clone();
            _this.parent().parent().find('.multiple-options').remove();
        }
    });

    // Add question
    $(document).on('click', '.add-question a', function () {
        let _this = $(this);

        if (_this.parent().parent().find('label').length < 6) {
            $(`<label>
                <input type="text" name="multiple_options[]" value="" required />
                <a href='javascript:void(0)' class="remove-question"><i class="fa fa-minus"></i></a>
            </label>`).insertBefore(_this.parent());
        }

        if (_this.parent().parent().find('label').length === 6) {
            _this.parent().remove();
        }
    });

    // Remove question
    $(document).on('click', '.remove-question', function () {
        let _this = $(this);

        if (_this.parent().parent().find('label').length <= 6 && _this.parent().parent().find('.add-question').length === 0) {
            _this.parent().parent().append(`<div class="add-question">
                                                <a href="javascript:void(0)">Add New <i class="fa fa-plus"></i></a>
                                            </div>`);
        }

        _this.parent().remove();
    });


    /**
     * Quiz JS 
     * 
     * */

    // function get_questions_ajax() {
    // }

    // Question selection functionality
    $(document).on('change', '.question-select', function () {
        let _this = $(this);
        let posttype = _this.data('posttype');
        let question_id = _this.val();
        let hierarchy_index = _this.data('index');
        let ques_name = _this.attr('name');

        $.ajax({
            type: 'post',
            cache: false,
            url: URLs.AJAX_URL,
            data: {
                action: URLs.PLUGIN_PREFIX + "_get_questions",
                posttype: posttype,
                question_id: question_id
            },
            success: function (res) {
                res = JSON.parse(res);
                if ((res.status === true || res.status === 1) && res.data != undefined) {
                    let data = res.data;
                    let condition = res.condition;
                    let ques_template = '';
                    let opt_template = '<div class="conditions">';

                    // Remove condition on other option selection
                    _this.parent().find('.conditions').remove();

                    // Append conditions
                    let cd_count = 1;
                    for (const key in data) {
                        if (Object.hasOwnProperty.call(data, key)) {
                            const ques = data[key];
                            opt_template += '<div>';
                            opt_template += '<span>';
                            opt_template += ques;
                            opt_template += ': ';
                            opt_template += '</span>';
                            opt_template += '<select class="conditions-select" name="' + ((ques_name != "" || ques_name != undefined) ? ques_name + "_" : "") + 'cd_' + cd_count + '" data-cd_index="' + cd_count + '" required>';
                            opt_template += '<option value="" selected disabled>Select condition...</option>';

                            for (const condition_key in condition) {
                                if (Object.hasOwnProperty.call(condition, condition_key)) {
                                    const elements = condition[condition_key];
                                    opt_template += '<option value="' + elements.type + '">';
                                    opt_template += elements.title;
                                    opt_template += '</option>';
                                }
                            }

                            opt_template += '</select>';
                            opt_template += '</div>';
                        }
                        cd_count++;
                    }
                    opt_template += '</div>';
                    _this.parent().append(opt_template);

                    // Append new question
                    /* let ques_clone = _this.clone();
                    ques_template += '<div>';
                    ques_template += '<select class="question-select" data-posttype="' + URLs.PLUGIN_PREFIX + '-questions">';
                    ques_template += '<option value="" selected="" disabled="">Select question...</option>';

                    for (let i = 0; i < ques_clone[0].length; i++) {
                        const options = ques_clone[0][i];
                        if (options.value != question_id && options.value != "") {
                            ques_template += '<option value="' + options.value + '">';
                            ques_template += options.textContent;
                            ques_template += '</option>';
                        }
                    }

                    ques_template += '</select>';
                    ques_template += '</div>';
                    _this.parent().parent().append(ques_template); */

                }
            }
        });
    });


    // Condition based options
    $(document).on('change', '.conditions-select', function () {
        let _this = $(this);
        let question_type = URLs.PLUGIN_PREFIX + '-questions';
        let value = _this.val();
        let ques_template = '';
        let prev_hierarchy_index = _this.parent().parent().prev().data('index');
        let next_hierarchy_index = parseInt(prev_hierarchy_index) + 1;
        let cd_index = _this.data('cd_index');
        let cd_name = _this.attr('name');

        // _this.parent().find('.opt-select').remove();
        _this.next().remove();

        if (question_type === value) {
            let ques_clone = $(this).parent().parent().prev().clone();
            let selected_val = $(this).parent().parent().prev().find('option:selected').val();

            // ques_template += '<select class="opt-select" required style="margin-left: 20px; display: block; width: 100%;">';
            ques_template += '<div style="margin-left: 20px;">';
            ques_template += '<select class="question-select" name="' + ((cd_name != '' || cd_name != undefined) ? cd_name + "_" : "") + 'qs_' + next_hierarchy_index + '" data-index="' + next_hierarchy_index + '" data-posttype="' + URLs.PLUGIN_PREFIX + '-questions" required>';
            ques_template += '<option value="" selected="" disabled="">Select question...</option>';

            for (let i = 0; i < ques_clone[0].length; i++) {
                const options = ques_clone[0][i];
                if (options.value != selected_val && options.value != "") {
                    ques_template += '<option value="' + options.value + '">';
                    ques_template += options.textContent;
                    ques_template += '</option>';
                }
            }

            ques_template += '</select>';
            ques_template += '</div>';
            _this.parent().append(ques_template);
        }
        else {
            $.ajax({
                type: 'post',
                cache: false,
                url: URLs.AJAX_URL,
                data: {
                    action: URLs.PLUGIN_PREFIX + "_condition_options",
                    value: value
                },
                success: function (res) {
                    res = JSON.parse(res);
                    let opt_template = '';

                    _this.next().remove();

                    if ((res.status === true || res.status === 1) && res.data != undefined) {
                        let data = res.data;
                        opt_template += '<div style="margin-left: 20px;">';
                        // opt_template += '<select class="opt-select" required>';
                        opt_template += '<select class="question-select" name="' + ((cd_name != '' || cd_name != undefined) ? cd_name + "_" : "") + 'page" data-posttype="" required>';
                        opt_template += '<option value="" selected disabled>Select question...</option>'
                        for (const key in data) {
                            if (Object.hasOwnProperty.call(data, key)) {
                                const element = data[key];
                                opt_template += '<option value="' + element.id + '">';
                                opt_template += element.title;
                                opt_template += '</option>';
                            }
                        }
                        opt_template += '</select>';
                        opt_template += '</div>';
                        _this.parent().append(opt_template);
                    }
                    else {
                        alert(res.msg);
                    }
                }
            });
        }

    });

    // Next question selection
    /* $(document).on('change', '.opt-select', function () {
        let _this = $(this);
        let opt_template = '';
        let ques_clone = $(this).parent().parent().prev().clone();
        let selected_val = $(this).parent().parent().prev().find('option:selected').val();
        let check_page = _this.prev().find('option:selected').val();

        _this.next('div').remove();

        if (check_page != 'page') {
            opt_template += '<div style="margin-left: 20px;">';
            opt_template += '<select class="question-select" data-posttype=' + URLs.PLUGIN_PREFIX + '"-questions" required >';
            opt_template += '<option value="" selected="" disabled="">Select question...</option>';

            for (let i = 0; i < ques_clone[0].length; i++) {
                const options = ques_clone[0][i];
                if (options.value != selected_val && options.value != "") {
                    opt_template += '<option value="' + options.value + '" ' + ((_this.val() === options.value) ? "selected" : "") + '>';
                    opt_template += options.textContent;
                    opt_template += '</option>';
                }
            }

            opt_template += '</select>';
            opt_template += '</div>';
            _this.parent().append(opt_template);
        }
    }); */
});