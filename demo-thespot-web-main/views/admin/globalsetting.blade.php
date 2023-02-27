@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Setting Management</h5>
    </div>
    <div class="card-block pb-0">
        <div class="table-responsive">
            <table class="table table-hover table-list">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Setting Name</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($config) > 0) {
                        $i = 0;

                        foreach ($config as $key => $value) {$i++; //dd($value['value'])?>
                            <tr>
                                <td><?= $i ?>.</td>
                                <td><?= $value->title ?></td>
                                <td colspan="2">
                                 {!! Form::open(['route' => 'updateSetting', 'class' => 'form-horizontal','style'=>'display:flex']) !!}
                                     <?php
                                        if ($value->type == 'textarea') {
                                            $boxAttr = ["type" => "textarea", 'value' => $value->value, 'id' => 'input-field-' . $value->id, "class" => "form-control summernote", 'label' => false];
                                        } else if ($value->type == 'number') {
                                            $boxAttr = ["type" => "text", 'value' => $value->value, 'id' => 'input-field-' . $value->id, 'lang' => $value->type,  "class" => "form-control", 'label' => false];
                                        } else if ($value->type == 'email') {
                                            $boxAttr = ["type" => "email", 'value' => $value->value, 'id' => 'input-field-' . $value->id, 'lang' => $value->type, "class" => "form-control", 'label' => false];
                                        } else {
                                            $boxAttr = ["type" => "text", 'value' => $value->value, 'id' => 'input-field-' . $value->id, 'lang' => $value->type,  "class" => "form-control", 'label' => false];
                                        }

                                        if (!empty($value->attr)) {
                                            $attr = $value->attr;
                                            $attr = unserialize($attr);
                                            if (gettype($attr) == 'array') {
                                                $boxAttr = array_merge($boxAttr, $attr);
                                            }
                                        }
                                        //pr(serialize(['required','onpaste'=>'return false;','maxlength'=>'3','min'=>'1','pattern'=>"[0-9]{3}"]));die;
                                        //dd($boxAttr);
                                        if (!empty($boxAttr)) {

                                            //echo $this->Form->input('value', $boxAttr);
                                        }
                                        ?>
                                        {!! Form::text($value['type'], $value['value'],$boxAttr) !!}
                                        <button type="button" id="<?php echo $value['id']; ?>" data-type="<?php echo $value['type']; ?>" data-slug="<?php echo $value['title']; ?>" data-slug-text="<?php echo $value['slug']; ?>" class="submit_config btn btn-primary"><i class="mdi mdi-spin mdi-loading hide" id="loading-msg-<?php echo $value['id']; ?>"></i> Update</button>
                                      {!! Form::close()  !!}
                                    <span class="help-block success_msg success_msg_<?php echo $value['id'] ?>" style="color: green;">&nbsp;</span>
                                    <span class="help-block error_msg error_msg_<?php echo $value['id']; ?>" style="color: red;"></span>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">
                                <center>No record found.</center>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
@section('customJs')
<script>
    function validUrl(url){

    }
    $('form').submit(function(){
        return false;
    });
    $(document).on('click', '.submit_config', function() {
        form = $(this).parent('form');
        var id = $(this).attr('id');
        $("#loading-msg-" + id).show();
        submitStatus = 1;
        var title = $(this).attr('data-slug');
        var typeSlug = $(this).attr('data-type');
        var slugtext = $(this).attr('data-slug-text');
        $(".success_msg,.error_msg").show();
        $(".success_msg").html('&nbsp;');
        $(".error_msg").html('');
        inputField = $("#input-field-" + id);
        var requiredAttr = inputField.attr('required');
        var value = inputField.val();
        console.warn($(this).attr('data-type'));
        if (typeSlug == 'email') {
            if (/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value)) {
                $(".error-msg").hide();
                $(".success-msg").hide();
                submit = true;
            } else {
                submit = false;
            }
        } else {
            submit = true;
        }
        if (requiredAttr) {
            if (value == "") {
                submitStatus = 0;
            }
        }
        if (submitStatus == 1) {
            if (submit) {
                $.ajax({
                    type: 'get',
                    url: "{{ url("/")}}/admin/updateglobalsetting",
                    data: {
                        value: value,
                        id: id
                    },
                    success: function(response) {
                        $("#loading-msg-" + id).hide();
                        if (response == true) {
                            $(".success_msg_" + id).text(title + ' has been updated.');
                            setTimeout(function() {
                                $(".success_msg_" + id).html('&nbsp;')
                            }, 4000);
                        } else {
                            $(".error_msg_" + id).text(title + ' not updated.');
                            setTimeout(function() {
                                $(".error_msg_" + id).hide();
                            }, 4000);
                        }
                    }
                });
            } else {
                $("#loading-msg-" + id).hide();
                $(".error_msg_" + id).text('Please enter valid ' + title);
                setTimeout(function() {
                    $(".error_msg_" + id).hide();
                }, 4000);
            }
        } else {
            $("#loading-msg-" + id).hide();
            $(".error_msg_" + id).text('Please enter ' + title);
            setTimeout(function() {
                $(".error_msg_" + id).hide();
            }, 4000);
        }
    });

</script>

@endsection

