// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#dataTable').DataTable(
        {
            "columnDefs": [
                {
                    "targets": [-1],
                    "orderable": false
                },
                {
                    "targets": 'posts_list_comments_count',
                    "orderable": false
                }
            ]
        }
    );
});