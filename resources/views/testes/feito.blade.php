<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>PRONAP</title>
    <!--CSS Personalizado-->
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/pronap.css')}}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
    <!-- Optional theme -->
    <link rel="stylesheet" href="{{url('assets/font-awesome/css/font-awesome.min.css')}}">
    <!-- Optional theme -->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap-theme.min.css')}}">
    <!-- Chamadas JS -->
    <!--jQuery-->
    <script src="{{url('assets/js/jquery-3.2.0.min.js')}}"></script>
    <script src="{{url('assets/js/bootstrap.min.js')}}"></script>

    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" />


</head>
<body>



<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th>First name</th>
        <th>Last name</th>
        <th>Position</th>
        <th>Office</th>
        <th>Start date</th>
        <th>Salary</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td>nono</td>
        <td>nono</td>
        <td>nono</td>
        <td>nono</td>
        <td>nono</td>
        <td>nono</td>
    </tr>
    </tbody>
</table>



<script>
    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {
        editor = new $.fn.dataTable.Editor( {
            ajax: "../php/staff.php",
            table: "#example",
            fields: [ {
                label: "First name:",
                name: "first_name"
            }, {
                label: "Last name:",
                name: "last_name"
            }, {
                label: "Position:",
                name: "position"
            }, {
                label: "Office:",
                name: "office"
            }, {
                label: "Extension:",
                name: "extn"
            }, {
                label: "Start date:",
                name: "start_date",
                type: "datetime"
            }, {
                label: "Salary:",
                name: "salary"
            }
            ]
        } );

        // Activate an inline edit on click of a table cell
        $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this, {
                buttons: { label: '&gt;', fn: function () { this.submit(); } }
            } );
        } );

        $('#example').DataTable( {
            dom: "Bfrtip",
            ajax: "../php/staff.php",
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                { data: "first_name" },
                { data: "last_name" },
                { data: "position" },
                { data: "office" },
                { data: "start_date" },
                { data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
            ],
            order: [ 1, 'asc' ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                { extend: "create", editor: editor },
                { extend: "edit",   editor: editor },
                { extend: "remove", editor: editor }
            ]
        } );
    } );
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>
</body>
</html>