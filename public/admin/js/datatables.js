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
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
            }
        }
    );
});