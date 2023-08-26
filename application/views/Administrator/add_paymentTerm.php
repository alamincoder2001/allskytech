<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="form-horizontal">
            <form onsubmit="paymentTermAdd(event)">
                <input type="hidden" id="id" name="id" />
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Term Name </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-8">
                        <input type="text" id="term" name="term" placeholder="Term Name" autocomplete="off" value="<?php echo set_value('term'); ?>" class="col-xs-10 col-sm-4" />
                        <span id="msg"></span>
                        <?php echo form_error('term'); ?>
                        <span style="color:red;font-size:15px;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 5px;">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                    <label class="col-sm-1 control-label no-padding-right"></label>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-sm btn-success">
                            Submit
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>



<div class="row">
    <div class="col-xs-12">

        <div class="clearfix">
            <div class="pull-right tableTools-container"></div>
        </div>
        <div class="table-header">
            Payment Term Information
        </div>
        <!-- div.table-responsive -->

        <!-- div.dataTables_borderWrap -->
        <div id="saveResult">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>SL No</th>
                        <th>Term Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($terms as $key => $item) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $item->term; ?></td>
                            <td>
                                <a style="cursor: pointer;" class="green" onclick="editTerm(event, <?php echo $item->id; ?>)">
                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                </a>

                                <a style="cursor: pointer;" class="red" onclick="deleteTerm(event, <?php echo $item->id; ?>)">
                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
        </div>
    </div>
</div>

<script>
    function paymentTermAdd(event) {
        event.preventDefault();
        let formdata = new FormData(event.target)
        $.ajax({
            url: "/insert_payment_term",
            method: "POST",
            dataType: 'JSON',
            data: formdata,
            processData: false,
            contentType: false,
            success: res => {
                if (res.status) {
                    alert(res.msg);
                    location.reload();
                }
            }
        })
    }

    function editTerm(event, id) {
        event.preventDefault();
        $.ajax({
            url: "/get_payment_term",
            method: "POST",
            data: {
                id: id
            },
            dataType: 'JSON',
            success: res => {
                $.each(res[0], (index, value) => {
                    $("form").find("#" + index).val(value);
                })
            }
        })
    }

    function deleteTerm(event, id) {
        event.preventDefault();
        if (confirm("Are you sure !")) {
            $.ajax({
                url: "/payment_term_delete",
                method: "POST",
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: res => {
                    if (res.status) {
                        alert(res.msg)
                        location.reload();
                    } else {
                        console.log(res.msg);
                    }
                }
            })
        }
    }
</script>